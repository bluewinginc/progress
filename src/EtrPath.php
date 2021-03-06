<?php

namespace Bluewing\Progress;

use Bluewing\Algorithms2015\AlgorithmManager;
use Bluewing\Algorithms2015\LongTerm\LongTermAdolescent;
use Bluewing\Algorithms2015\LongTerm\LongTermAdult;
use Bluewing\Algorithms2015\LongTerm\LongTermChild;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdult;
use Bluewing\Algorithms2015\ShortTerm\ShortTermChild;
use Bluewing\Progress\Structs\EtrPathStruct;
use InvalidArgumentException;

class EtrPath
{
    /** @var EtrPathStruct|null */
    protected $data = null;

    /** @var LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild|null $algorithm */
    protected $algorithm = null;

    /** @var RatingCollection|null $ratings */
    protected $ratings = null;

    /** @var array $values */
    protected $values = [];

    /**
     * EtrPath constructor.
     *
     * @param int $raterAgeGroup
     * @param RatingCollection $ratings
     */
    public function __construct(int $raterAgeGroup, RatingCollection $ratings)
    {
        $this->data = new EtrPathStruct;

        $this->ratings = $ratings;
        $this->data->raterAgeGroup = $raterAgeGroup;

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

        $this->algorithm = $manager->getFor($this->data->raterAgeGroup, $this->ratings->count());

        if ($this->ratings->count() === 0) {
            throw new InvalidArgumentException('There are no ratings.');
        }

        $this->data->firstRating = $this->ratings->first();

        if ($this->data->firstRating->score < 0 || $this->data->firstRating->score > 40) {
            throw new InvalidArgumentException('The first rating score is invalid. It must be between 0.0 and 40.0.');
        }

        $this->data->meetings = $this->ratings->count();
        $this->data->values = $this->calculateValues();
    }

    /**
     * Return the EtrPathStruct.
     *
     * @return EtrPathStruct
     */
    public function data() : EtrPathStruct
    {
        return $this->data;
    }

    /**
     * Return the expected treatment response (etr) for each meeting.
     * Return an array with the following fields: meeting, caption, value
     *
     * 1 Skipped
     * 2 20.2
     *
     * @return array
     * @throws InvalidArgumentException
     */
    private function calculateValues() : array
    {
        $flattenMeeting = $this->algorithm->flattenMeeting;
        $maxMeetings = $this->algorithm->maxMeetings;
        $centeredAt20 = $this->data->firstRating->score - 20;
        $interceptMean = $this->algorithm->interceptMean + ($this->algorithm->intake * $centeredAt20);
        $linearMean = $this->algorithm->linearMean + ($this->algorithm->linearByIntake * $centeredAt20);
        $quadraticMean = $this->algorithm->quadraticMean + ($this->algorithm->quadraticByIntake * $centeredAt20);
        $cubicMean = $this->algorithm->cubicMean + ($this->algorithm->cubicByIntake * $centeredAt20);
        $intercept = 1;

        // Make sure that the entire etr is always presented.
        if ($this->data->meetings < $maxMeetings) {
            $this->data->meetings = $maxMeetings;
        }

        // Add the intake session.
        $values = [];
        $values[] = $this->data->firstRating->score;

        // Add the remaining values.
        for ($i = 1; $i < $this->data->meetings; $i++) {
            $meeting = $i;

            if ($meeting >= $flattenMeeting) {
                $meeting = $flattenMeeting;
            }

            $linear	= $meeting;
            $quadratic = $linear * $linear;
            $cubic = $linear * $linear * $linear;
            $value = ($interceptMean * $intercept) + ($linearMean * $linear) + ($quadraticMean * $quadratic) + ($cubicMean * $cubic);
            $values[] = round($value, 1, PHP_ROUND_HALF_UP);
        }

        return $values;
    }
}