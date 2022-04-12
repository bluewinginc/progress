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

class EtrMtgTarget
{
    protected EtrStruct|null $data = null;
    protected Rater|null $rater = null;
    protected RatingCollection|null $ratings = null;
    protected LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild|null $algorithm = null;

    /**
     * EtrMtgTarget constructor.
     * This uses the algorithm that is appropriate for the rater age group and number of meetings.
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

        $this->algorithm = $manager->getFor($this->rater->data()->ageGroup, $this->ratings->count());

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
        $this->data->value = round($this->value($firstRatingScore, $this->ratings->count()), 2);
        $this->data->valueAsString = number_format($this->data->value, 2);
    }

    /**
     * Return the EtrMtgTarget data.
     *
     * @return EtrStruct
     */
    #[Pure] public function data(): EtrStruct
    {
        if ($this->ratings->count() === 0) return new EtrStruct;

        return $this->data;
    }

    /**
     * Return the expected change.  This is the etr meeting target value - first rating score.
     *
     * @param float $firstRatingScore
     * @return float
     */
    #[Pure] private function expectedChange(float $firstRatingScore): float
    {
        return $this->value($firstRatingScore, $this->ratings->count()) - $firstRatingScore;
    }

    /**
     * Return a boolean indicating if an OPEN rater met or exceeded the etr meeting target.
     * This pertains to progress calculations, progress meter, stats.
     * We look at the last rating score.
     * This DOES NOT pertain to graph.
     * One (1) rating is REQUIRED.
     *
     * @param float $firstRatingScore
     * @param float $lastRatingScore
     * @return bool
     */
    #[Pure] private function met(float $firstRatingScore, float $lastRatingScore): bool
    {
        return $lastRatingScore >= $this->value($firstRatingScore, $this->ratings->count());
    }

    /**
     * Return a value representing the percentage of the etr target met for an OPEN rater only.
     * It is possible for the etrMeetingTargetMetPercent to be greater than 100, so the ceiling is 100 percent.
     * One (1) rating is REQUIRED.
     *
     * @param float $firstRatingScore
     * @param float $lastRatingScore
     * @return float
     */
    #[Pure] private function metPercent(float $firstRatingScore, float $lastRatingScore): float
    {
        // INFO: When the expected_change is 0.0, a division by 0 error can happen.
        //  When the expected change is less than 0.0, it means the first rating score is above 32.
        //  In these cases always return 0.0.

        if ($this->expectedChange($firstRatingScore) <= 0.0) return 0.0;

        $change = ($lastRatingScore - $firstRatingScore);

        $etrTargetMeetingMetPercent = ($change / $this->expectedChange($firstRatingScore)) * 100;

        if ($etrTargetMeetingMetPercent > (float)100) return 100.0;
        if ($etrTargetMeetingMetPercent < (float)0) return 0.0;

        return $etrTargetMeetingMetPercent;
    }

    /**
     * Return a boolean indicating if an OPEN rater has met the predicted change
     * at a specific percentage.
     * This pertains to progress calculations, progress meter, and stats
     * This DOES NOT pertain to graph.
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
            throw new InvalidArgumentException('The predictedChangeIndex parameter is invalid.  It must be between 0.0 and 100.0.');
        }

        $change = $lastRatingScore - $firstRatingScore;

        return ($change / $this->expectedChange($firstRatingScore)) >= ($predictedChangeIndex / 100);
    }

    /**
     * Return a value representing an etr value for an OPEN rater for a specific meeting.
     * Filter through the ratings and create an array of rating scores.  IGNORE skipped ratings.
     * One (1) ORS score is REQUIRED.
     *
     * @param float $firstRatingScore
     * @param int $meeting
     * @return float
     */
    #[Pure] public function value(float $firstRatingScore, int $meeting): float
    {
        if ($meeting < 1 || $meeting > $this->ratings->count()) return 0.0;

        $flattenMeeting = $this->algorithm->flattenMeeting;
        $centeredAt20 = $firstRatingScore - 20;
        $interceptMean = $this->algorithm->interceptMean + ($this->algorithm->intake * $centeredAt20);
        $linearMean = $this->algorithm->linearMean + ($this->algorithm->linearByIntake * $centeredAt20);
        $quadraticMean = $this->algorithm->quadraticMean + ($this->algorithm->quadraticByIntake * $centeredAt20);
        $cubicMean = $this->algorithm->cubicMean + ($this->algorithm->cubicByIntake * $centeredAt20);
        $intercept = 1;

        if ($meeting === 1) return $firstRatingScore;     // Intake meeting

        // INFO: This section of code uses the algorithm's flatten_meeting property to flatten the trajectory of the etr
        //  at the outer tail as it can get erratic (it drops after it reaches the max altitude).

        $i = $meeting - 1;

        if ($i >= $flattenMeeting) $i = $flattenMeeting;

        $linear	= $i;
        $quadratic = $linear * $linear;
        $cubic = $linear * $linear * $linear;

        return ($interceptMean * $intercept) + ($linearMean * $linear) + ($quadraticMean * $quadratic) + ($cubicMean * $cubic);
    }
}