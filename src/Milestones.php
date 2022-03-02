<?php

namespace Bluewing\Progress;

use Bluewing\Algorithms2015\LongTerm\LongTermAdolescent;
use Bluewing\Algorithms2015\LongTerm\LongTermAdult;
use Bluewing\Algorithms2015\LongTerm\LongTermChild;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdult;
use Bluewing\Algorithms2015\ShortTerm\ShortTermChild;
use Bluewing\Progress\Structs\MilestonesStruct;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

class Milestones
{
    protected MilestonesStruct|null $data = null;
    protected Rating|null $firstRating = null;
    protected Rating|null $lastRating = null;
    protected LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild|null $algorithm = null;
    protected RatingCollection|null $ratings = null;
    protected float $change = 0.0;

    /**
     * Milestones constructor.
     *
     * @param LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild $algorithm
     * @param RatingCollection $ratings
     */
    public function __construct(LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild $algorithm, RatingCollection $ratings)
    {
        $this->algorithm = $algorithm;
        $this->ratings = $ratings;

        $this->data = new MilestonesStruct;

        $this->calculateAndPopulateData();
    }

    /**
     * Calculate and populate data.
     * @return void
     */
    private function calculateAndPopulateData() : void
    {
        if ($this->ratings->count() < 2) {
            $this->data->cscMet = false;
            $this->data->rcMet = false;
            $this->data->rcOrCscMet = false;
            return;
        }

        $this->firstRating = $this->ratings->first();

        if ($this->firstRating->data()->score < 0 || $this->firstRating->data()->score > 40) {
            throw new InvalidArgumentException('The first rating score is invalid. It must be between 0.0 and 40.0.');
        }

        $this->lastRating = $this->ratings->last();

        if ($this->lastRating->data()->score < 0 || $this->lastRating->data()->score > 40) {
            throw new InvalidArgumentException('The last rating score is invalid. It must be between 0.0 and 40.0.');
        }

        $this->change = ($this->lastRating->data()->score - $this->firstRating->data()->score);

        $this->data->cscMet = $this->cscMet();
        $this->data->rcMet = !$this->data->cscMet && $this->rcMet();
        $this->data->rcOrCscMet = $this->data->rcMet || $this->data->cscMet;
    }

    /**
     * Return the MilestonesStruct.
     *
     * @return MilestonesStruct
     */
    public function data() : MilestonesStruct
    {
        return $this->data;
    }

    /**
     * Determine if an OPEN or CLOSED rater met clinically significant change.
     * There must be at least two (2) rating scores.
     *
     * @return bool
     */
    #[Pure] private function cscMet() : bool
    {
        if (! $this->rcMet()) {
            return false;
        }

        if ($this->firstRating->data()->score > $this->algorithm->clinicalCutoff) {
            return false;
        }

        return ($this->lastRating->data()->score > $this->algorithm->clinicalCutoff);
    }

    /**
     * Determine if a rater has met reliable change.
     * A minimum of two (2) rating scores are required.
     *
     * @return bool
     */
    private function rcMet() : bool
    {
        return ($this->change >= $this->algorithm->reliableChangeIndex);
    }
}