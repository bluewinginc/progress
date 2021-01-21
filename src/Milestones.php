<?php

namespace Bluewing\Progress;

use Bluewing\Algorithms2015\LongTerm\LongTermAdolescent;
use Bluewing\Algorithms2015\LongTerm\LongTermAdult;
use Bluewing\Algorithms2015\LongTerm\LongTermChild;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdult;
use Bluewing\Algorithms2015\ShortTerm\ShortTermChild;
use Bluewing\Progress\Structs\MilestonesStruct;
use Bluewing\Progress\Structs\RatingStruct;
use InvalidArgumentException;

class Milestones
{
    /** @var MilestonesStruct|null $data */
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

    /**
     * Milestones constructor.
     *
     * @param LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild $algorithm
     * @param RatingCollection $ratings
     */
    public function __construct($algorithm, RatingCollection $ratings)
    {
        $this->algorithm = $algorithm;
        $this->ratings = $ratings;

        $this->data = new MilestonesStruct;

        $this->calculateAndPopulateData();
    }

    /**
     * Calculate and populate data.
     *
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

        if ($this->firstRating->score < 0 || $this->firstRating->score > 40) {
            throw new InvalidArgumentException('The first rating score is invalid. It must be between 0.0 and 40.0.');
        }

        $this->lastRating = $this->ratings->last();

        if ($this->lastRating->score < 0 || $this->lastRating->score > 40) {
            throw new InvalidArgumentException('The last rating score is invalid. It must be between 0.0 and 40.0.');
        }

        $this->ratingChange = ($this->lastRating->score - $this->firstRating->score);

        $this->data->cscMet = $this->cscMet();
        $this->data->rcMet = $this->data->cscMet ? false : $this->rcMet();
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
     * Determine whether or not an OPEN or CLOSED rater met clinically significant change.
     * There must be at least two (2) rating scores.
     *
     * @return bool
     */
    private function cscMet() : bool
    {
        if (! $this->rcMet()) {
            return false;
        }

        if ($this->firstRating->score > $this->algorithm->clinicalCutoff) {
            return false;
        }

        return ($this->lastRating->score > $this->algorithm->clinicalCutoff);
    }

    /**
     * Determine if a rater has met reliable change.
     * A minimum of two (2) rating scores are required.
     *
     * @return bool
     */
    private function rcMet() : bool
    {
        return ($this->ratingChange >= $this->algorithm->reliableChangeIndex);
    }
}