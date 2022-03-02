<?php

namespace Bluewing\Progress;

use Bluewing\Algorithms2015\AlgorithmManager;
use Bluewing\Algorithms2015\LongTerm\LongTermAdolescent;
use Bluewing\Algorithms2015\LongTerm\LongTermAdult;
use Bluewing\Algorithms2015\LongTerm\LongTermChild;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdult;
use Bluewing\Algorithms2015\ShortTerm\ShortTermChild;
use Bluewing\Progress\Structs\EtrMtgTargetStruct;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

class EtrMtgTarget
{
    protected EtrMtgTargetStruct|null $data = null;
    protected Rating|null $firstRating = null;
    protected Rating|null $lastRating = null;
    protected LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild|null $algorithm = null;
    protected float $ratingChange = 0.0;
    protected Rater|null $rater = null;
    protected RatingCollection|null $ratings = null;

    /**
     * EtrMtgTarget constructor.
     * This uses the algorithm that is appropriate for the rater age group and number of meetings.
     *
     * @param Rater $rater
     * @param RatingCollection $ratings
     */
    public function __construct(Rater $rater, RatingCollection $ratings)
    {
        $this->data = new EtrMtgTargetStruct;

        $this->rater = $rater;

        $this->ratings = $ratings;

        $this->calculateAndPopulateData();
    }

    /**
     * Calculate and populate the data.
     *
     * @return void
     */
    public function calculateAndPopulateData() : void
    {
        $manager = new AlgorithmManager;

        $this->algorithm = $manager->getFor($this->rater->data()->ageGroup, $this->ratings->count());

        $this->firstRating = $this->ratings->first();

        if ($this->ratings->count() <= 1 || $this->firstRating->data()->score > 32.0) {
            $this->ratingChange = 0.0;

            $this->data->expectedChange = 0.0;
            $this->data->met = false;
            $this->data->metPercent = 0.0;
            $this->data->metPercent50 = false;
            $this->data->metPercent67 = false;
            $this->data->value = 0.0;

            return;
        }

        if ($this->firstRating->data()->score < 0 || $this->firstRating->data()->score > 40) {
            throw new InvalidArgumentException('The first rating score is invalid. It must be between 0.0 and 40.0.');
        }

        $this->lastRating = $this->ratings->last();

        if ($this->lastRating->data()->score < 0 || $this->lastRating->data()->score > 40) {
            throw new InvalidArgumentException('The last rating score is invalid. It must be between 0.0 and 40.0.');
        }

        $this->ratingChange = ($this->lastRating->data()->score - $this->firstRating->data()->score);

        $this->data->expectedChange = $this->expectedChange();
        $this->data->met = $this->met();
        $this->data->metPercent = $this->metPercent();
        $this->data->metPercent50 = $this->predictedChangePercentMet(50.0);
        $this->data->metPercent67 = $this->predictedChangePercentMet(66.66);
        $this->data->value = $this->value($this->ratings->count());
    }

    /**
     * Return the EtrMtgTarget data.
     *
     * @return EtrMtgTargetStruct
     */
    #[Pure] public function data() : EtrMtgTargetStruct
    {
        if ($this->ratings->count() === 0) {
            return new EtrMtgTargetStruct;
        }

        return $this->data;
    }

    /**
     * Return the expected change.  This is the etr meeting target value - first rating score.
     *
     * @return float
     */
    #[Pure] private function expectedChange() : float
    {
        return $this->value($this->ratings->count()) - $this->firstRating->data()->score;
    }

    /**
     * Return a boolean indicating if an OPEN rater met or exceeded the etr meeting target.
     * This pertains to progress calculations, progress meter, stats.
     * We look at the last rating score.
     * This DOES NOT pertain to graph.
     * One (1) rating is REQUIRED.
     *
     * @return bool
     */
    #[Pure] private function met() : bool
    {
        return $this->lastRating->data()->score >= $this->value($this->ratings->count());
    }

    /**
     * Return a value representing the percentage of the etr target met for an OPEN rater only.
     * It is possible for the etrMeetingTargetMetPercent to be greater than 100, so the ceiling is 100 percent.
     * One (1) rating is REQUIRED.
     *
     * @return float
     */
    #[Pure] private function metPercent() : float
    {
        // When the expected_change is 0.0, a division by 0 error can happen.
        // When the expected change is less than 0.0, it means the first rating score is above 32.
        // In these cases always return 0.0.
        if ($this->expectedChange() <= 0.0) {
            return 0.0;
        }

        $etrTargetMeetingMetPercent = (float)(($this->ratingChange / $this->expectedChange()) * 100);

        if ($etrTargetMeetingMetPercent > (float)100) {
            return 100.0;
        } else if ($etrTargetMeetingMetPercent < (float)0) {
            return 0.0;
        } else {
            return $etrTargetMeetingMetPercent;
        }
    }

    /**
     * Return a boolean indicating if an OPEN rater has met the predicted change
     * at a specific percentage.
     * This pertains to progress calculations, progress meter, and stats
     * This DOES NOT pertain to graph.
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
            return 0.0;
        }

        if ($predictedChangeIndex < 0 || $predictedChangeIndex > 100) {
            throw new InvalidArgumentException('The predictedChangeIndex parameter is invalid.  It must be between 0.0 and 100.0.');
        }

        return ($this->ratingChange / $this->expectedChange()) >= ($predictedChangeIndex / 100);
    }

    /**
     * Return a value representing an etr value for an OPEN rater for a specific meeting.
     * Filter through the ratings and create an array of rating scores.  IGNORE skipped ratings.
     * One (1) ORS score is REQUIRED.
     *
     * @param int $meeting
     * @return float
     */
    #[Pure] public function value(int $meeting = 0) : float
    {
        if ($meeting < 1 || $meeting > $this->ratings->count()) {
            return 0.0;
        }

        $flattenMeeting = $this->algorithm->flattenMeeting;
        $centeredAt20 = $this->firstRating->data()->score - 20;
        $interceptMean = $this->algorithm->interceptMean + ($this->algorithm->intake * $centeredAt20);
        $linearMean = $this->algorithm->linearMean + ($this->algorithm->linearByIntake * $centeredAt20);
        $quadraticMean = $this->algorithm->quadraticMean + ($this->algorithm->quadraticByIntake * $centeredAt20);
        $cubicMean = $this->algorithm->cubicMean + ($this->algorithm->cubicByIntake * $centeredAt20);
        $intercept = 1;

        if ($meeting === 1) {
            return $this->firstRating->data()->score;     // Intake meeting
        }

        // This section of code uses the algorithm's flatten_meeting property to flatten the trajectory of the etr
        // at the outer tail as it can get erratic (it drops after it reaches the max altitude).

        $i = $meeting - 1;

        if ($i >= $flattenMeeting) {
            $i = $flattenMeeting;
        }

        $linear	= $i;
        $quadratic = $linear * $linear;
        $cubic = $linear * $linear * $linear;

        return ($interceptMean * $intercept) + ($linearMean * $linear) + ($quadraticMean * $quadratic) + ($cubicMean * $cubic);
    }
}