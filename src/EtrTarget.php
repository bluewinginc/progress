<?php

namespace Bluewing\Progress;

use Bluewing\Algorithms2015\AlgorithmManager;
use Bluewing\Algorithms2015\LongTerm\LongTermAdolescent;
use Bluewing\Algorithms2015\LongTerm\LongTermAdult;
use Bluewing\Algorithms2015\LongTerm\LongTermChild;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdult;
use Bluewing\Algorithms2015\ShortTerm\ShortTermChild;
use Bluewing\Progress\Structs\EtrStruct;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

class EtrTarget
{
    protected EtrStruct|null $data = null;
    protected Rater|null $rater = null;
    protected RatingCollection|null $ratings = null;
    protected LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild|null $algorithm = null;

    /**
     * EtrTargetValue constructor.
     *
     * @param Rater $rater
     * @param RatingCollection $ratings
     */
    public function __construct(Rater $rater, RatingCollection $ratings)
    {
        $this->data = new EtrStruct;

        $this->rater = $rater;
        $this->ratings = $ratings;

        $this->calculateAndPopulateData();
    }

    /**
     * Calculate and populate the data.
     *
     * @return void
     */
    private function calculateAndPopulateData(): void
    {
        $manager = new AlgorithmManager;

        // INFO: Always use the Short Term Algorithm.  There are two reasons for this.
        //  1. The short-term target is not as difficult to reach than the long term target.
        //  2. Most people will be served in less than 9 meetings, which uses the short-term algorithm.

        $this->algorithm = $manager->getFor($this->rater->data()->ageGroup, 0);

        $firstRating = $this->ratings->first();

        if ($this->ratings->count() <= 1 || $firstRating->data()->score > 32.0) {
            $this->data->expectedChange = 0.0;
            $this->data->expectedChangeAsString = number_format($this->data->expectedChange, 2);
            $this->data->met = false;
            $this->data->metPercent = 0.0;
            $this->data->metPercentAsString = number_format($this->data->metPercent, 2);
            $this->data->metPercent50 = false;
            $this->data->metPercent67 = false;
            $this->data->value = 0.0;
            $this->data->valueAsString = number_format($this->data->value, 2);

            return;
        }

        if ($firstRating->data()->score < 0 || $firstRating->data()->score > 40) {
            throw new InvalidArgumentException('The first rating score is invalid. It must be between 0.0 and 40.0.');
        }

        $firstRatingScore = $firstRating->data()->score;

        $lastRating = $this->ratings->last();

        if ($lastRating->data()->score < 0 || $lastRating->data()->score > 40) {
            throw new InvalidArgumentException('The last rating score is invalid. It must be between 0.0 and 40.0.');
        }

        $lastRatingScore = $lastRating->data()->score;

        $this->data->expectedChange = round($this->expectedChange($firstRatingScore), 2);
        $this->data->expectedChangeAsString = number_format($this->data->expectedChange, 2);
        $this->data->met = $this->met($firstRatingScore, $lastRatingScore);
        $this->data->metPercent = round($this->metPercent($firstRatingScore, $lastRatingScore), 2);
        $this->data->metPercentAsString = number_format($this->data->metPercent, 2);
        $this->data->metPercent50 = $this->predictedChangePercentMet($firstRatingScore, $lastRatingScore,50.0);
        $this->data->metPercent67 = $this->predictedChangePercentMet($firstRatingScore, $lastRatingScore,66.66);
        $this->data->value = round($this->value($firstRatingScore), 2);
        $this->data->valueAsString = number_format($this->data->value, 2);
    }

    /**
     * Return the EtrStruct data.
     *
     * @return EtrStruct
     */
    #[Pure] public function data(): EtrStruct
    {
        if ($this->ratings->count() === 0) return new EtrStruct;

        return $this->data;
    }

    /**
     * Return the expected change.  This is the etr target value - first rating score.
     *
     * @param float $firstRatingScore
     * @return float
     */
    #[Pure] private function expectedChange(float $firstRatingScore): float
    {
        return $this->value($firstRatingScore) - $firstRatingScore;
    }

    /**
     * Return a boolean indicating if a CLOSED rater met or exceeded the etr target.
     * One (1) rating score REQUIRED.
     *
     * @param float $firstRatingScore
     * @param float $lastRatingScore
     * @return bool
     */
    #[Pure] private function met(float $firstRatingScore, float $lastRatingScore): bool
    {
        return $lastRatingScore >= $this->value($firstRatingScore);
    }

    /**
     * Return a value representing the percentage of the etr target met for a CLOSED rater only.
     * It is possible for the etrTargetMetPercent to be greater than 100, so the ceiling is 100 percent.
     * One (1) rating score REQUIRED.
     *
     * @param float $firstRatingScore
     * @param float $lastRatingScore
     * @return float
     */
    #[Pure] private function metPercent(float $firstRatingScore, float $lastRatingScore): float
    {
        // INFO: When the expected_change is 0.0, a division by 0 error can happen.
        //  When the expected change is less than 0.0, it means the first score is above 32.
        //  In these cases always return 0.0.

        if ($this->expectedChange($firstRatingScore) <= 0.0) return 0.0;

        $change = ($lastRatingScore - $firstRatingScore);

        $etrTargetMetPercent = (float)(($change / $this->expectedChange($firstRatingScore)) * 100);

        if ($etrTargetMetPercent > (float)100) return 100.0;
        if ($etrTargetMetPercent < (float)0) return 0.0;

        return $etrTargetMetPercent;
    }

    /**
     * Return a boolean indicating if a CLOSED rater has met the predicted change at a specific percentage.
     * Two (2) rating scores are REQUIRED.
     *
     * @param float $firstRatingScore
     * @param float $lastRatingScore
     * @param float $predictedChangeIndex
     * @return bool
     */
    private function predictedChangePercentMet(float $firstRatingScore, float $lastRatingScore, float $predictedChangeIndex): bool
    {
        // INFO: When the expected_change is 0.0, a division by 0 error can happen.
        //  When the expected change is less than 0.0, it means the first rating score is above 32.
        //  In these cases always return 0.0.

        if ($this->expectedChange($firstRatingScore) <= 0.0) return 0.0;

        if ($predictedChangeIndex < 0 || $predictedChangeIndex > 100) {
            throw new InvalidArgumentException('The $predictedChangeIndex parameter is invalid. It must be between 0.0 and 100.0.');
        }

        $change = $lastRatingScore - $firstRatingScore;

        return (($change / $this->expectedChange($firstRatingScore)) >= ($predictedChangeIndex / 100));
    }

    /**
     * Return a value that represents the etr target.
     * It is the highest score on the ETR.
     * This is the value that is displayed on stats labeled Target.
     * In this method, we purposefully are choosing to use the ST algorithm.
     * Returns the expected treatment response (etr) target value.  Uses the ST algorithm.
     * One (1) rating score REQUIRED.
     *
     * @param float $firstRatingScore
     * @return float
     */
    #[Pure] private function value(float $firstRatingScore): float
    {
        $flattenMeeting = $this->algorithm->flattenMeeting;
        $centeredAt20 = $firstRatingScore - 20;
        $interceptMean = $this->algorithm->interceptMean + ($this->algorithm->intake * $centeredAt20);
        $linearMean = $this->algorithm->linearMean + ($this->algorithm->linearByIntake * $centeredAt20);
        $quadraticMean = $this->algorithm->quadraticMean + ($this->algorithm->quadraticByIntake * $centeredAt20);
        $cubicMean = $this->algorithm->cubicMean + ($this->algorithm->cubicByIntake * $centeredAt20);
        $intercept = 1;

        $linear	= $flattenMeeting;
        $quadratic = $linear * $linear;
        $cubic = $linear * $linear * $linear;
        $value = ($interceptMean * $intercept) + ($linearMean * $linear) + ($quadraticMean * $quadratic) + ($cubicMean * $cubic);

        return (float)$value;
    }
}