<?php

namespace Bluewing\Progress;

use Bluewing\Algorithms2015\AlgorithmManager;
use Bluewing\Algorithms2015\LongTerm\LongTermAdolescent;
use Bluewing\Algorithms2015\LongTerm\LongTermAdult;
use Bluewing\Algorithms2015\LongTerm\LongTermChild;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdult;
use Bluewing\Algorithms2015\ShortTerm\ShortTermChild;
use Bluewing\Progress\Structs\EtrTargetStruct;
use Bluewing\Progress\Structs\RatingStruct;
use InvalidArgumentException;

class EtrTarget
{
    /** @var EtrTargetStruct|null $data */
    protected $data = null;

    /** @var RatingStruct|null $firstRating */
    protected $firstRating = null;

    /** @var RatingStruct|null $lastRating */
    protected $lastRating = null;

    /** @var LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild|null $algorithm */
    protected $algorithm = null;

    /** @var float $ratingChange */
    protected $ratingChange = 0.0;

    /** @var RatingCollection|null $ratings */
    protected $ratings = null;

    /** @var int $raterAgeGroup */
    protected $raterAgeGroup = 0;

    /**
     * EtrTargetValue constructor.
     *
     * @param int $raterAgeGroup
     * @param RatingCollection $ratings
     */
    public function __construct(int $raterAgeGroup, RatingCollection $ratings)
    {
        $this->data = new EtrTargetStruct;

        $this->raterAgeGroup = $raterAgeGroup;

        $this->ratings = $ratings;

        $this->calculateAndPopulateData();
    }

    /**
     * Calculate and populate data.
     *
     * @return void
     */
    private function calculateAndPopulateData() : void
    {
        $manager = new AlgorithmManager;

        // Always use the Short Term Algorithm.  There are two reasons for this.
        // 1. The short-term target is not as difficult to reach than the long term target.
        // 2. Most people fit will be served in less than 9 meetings, which uses the short-term algorithm.

        $this->algorithm = $manager->getFor($this->raterAgeGroup, 0);

        $this->firstRating = $this->ratings->first();

        if ($this->ratings->count() <= 1 || $this->firstRating->score > 32.0) {
            $this->ratingChange = 0.0;

            $this->data->expectedChange = 0.0;
            $this->data->met = false;
            $this->data->metPercent = 0.0;
            $this->data->value = 0.0;
            $this->data->metPercent50 = false;
            $this->data->metPercent67 = false;

            return;
        }

        if ($this->firstRating->score < 0 || $this->firstRating->score > 40) {
            throw new InvalidArgumentException('The first rating score is invalid. It must be between 0.0 and 40.0.');
        }

        $this->lastRating = $this->ratings->last();

        if ($this->lastRating->score < 0 || $this->lastRating->score > 40) {
            throw new InvalidArgumentException('The last rating score is invalid. It must be between 0.0 and 40.0.');
        }

        $this->ratingChange = ($this->lastRating->score - $this->firstRating->score);

        $this->data->expectedChange = $this->expectedChange();
        $this->data->met = $this->met();
        $this->data->metPercent = $this->metPercent();
        $this->data->value = $this->value();
        $this->data->metPercent50 = $this->predictedChangePercentMet(50.0);
        $this->data->metPercent67 = $this->predictedChangePercentMet(66.66);
    }

    /**
     * Return the EtrTargetStruct data.
     *
     * @return EtrTargetStruct
     */
    public function data() : EtrTargetStruct
    {
        if ($this->ratings->count() === 0) {
            return new EtrTargetStruct;
        }

        return $this->data;
    }

    /**
     * Return the expected change.  This is the etr target value - first rating score.
     *
     * @return float
     */
    private function expectedChange() : float
    {
        return $this->value() - $this->firstRating->score;
    }

    /**
     * Return a boolean indicating whether or not a CLOSED rater met or exceeded the etr target.
     * One (1) rating score REQUIRED.
     *
     * @return bool
     */
    private function met() : bool
    {
        return $this->lastRating->score >= $this->value();
    }

    /**
     * Return a value representing the percentage of the etr target met for a CLOSED rater only.
     * It is possible for the etrTargetMetPercent to be greater than 100, so the ceiling is 100 percent.
     * One (1) rating score REQUIRED.
     *
     * @return float
     * @throws InvalidArgumentException
     */
    private function metPercent() : float
    {
        // When the expected_change is 0.0, a division by 0 error can happen.
        // When the expected change is less than 0.0, it means the first score is above 32.
        // In these cases always return 0.0.
        if ($this->expectedChange() <= 0.0) {
            return (float)0.0;
        }

        $etrTargetMetPercent = (float)(($this->ratingChange / $this->expectedChange()) * 100);

        if ($etrTargetMetPercent > (float)100) {
            return (float)100.0;
        } else if ($etrTargetMetPercent < (float)0) {
            return (float)0;
        } else {
            return $etrTargetMetPercent;
        }
    }

    /**
     * Return a boolean indicating whether or not a CLOSED rater has met the predicted change at a specific percentage.
     * Two (2) rating scores are REQUIRED.
     *
     * @param float $predictedChangeIndex
     * @return bool
     */
    private function predictedChangePercentMet(float $predictedChangeIndex) : bool
    {
        // When the expected_change is 0.0, a division by 0 error can happen.
        // When the expected change is less than 0.0, it means the first rating score is above 32.
        // In these cases always return 0.0.
        if ($this->expectedChange() <= 0.0) {
            return (float)0.0;
        }

        $predictedChangeIndex = (float)$predictedChangeIndex;

        if ($predictedChangeIndex < 0 || $predictedChangeIndex > 100) {
            throw new InvalidArgumentException('The $predictedChangeIndex parameter is invalid. It must be between 0.0 and 100.0.');
        }

        return (($this->ratingChange / $this->expectedChange()) >= ($predictedChangeIndex / 100));
    }

    /*
     * Return a value that represents the etr target.
     * It is the highest score on the ETR.
     * This is the value that is displayed on stats labeled Target.
     * In this method, we purposefully are choosing to use the ST algorithm.
     * Returns the expected treatment response (etr) target value.  Uses the ST algorithm.
     * One (1) rating score REQUIRED.
     *
     * @return float
     */
    private function value() : float
    {
        $flattenMeeting = $this->algorithm->flattenMeeting;
        $centeredAt20 = $this->firstRating->score - 20;
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