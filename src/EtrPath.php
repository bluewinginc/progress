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
    protected EtrPathStruct|null $data = null;
    protected LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild|null $algorithm = null;
    protected array $values = [];

    /**
     * EtrPath constructor.
     *
     * @param Rater $rater
     * @param Rating $firstRating
     * @param int $meetings
     */
    public function __construct(Rater $rater, Rating $firstRating, int $meetings)
    {
        if ($meetings <= 0) {
            throw new InvalidArgumentException('There meetings argument must be greater than 0.');
        }

        $this->data = new EtrPathStruct;

        $this->data->rater = $rater;
        $this->data->firstRating = $firstRating;
        $this->data->meetings = $meetings;

        $this->calculateAndPopulateData();
    }

    /**
     * Calculate and populate data.
     *
     * @return void
     */
    private function calculateAndPopulateData(): void
    {
        $manager = new AlgorithmManager;

        $this->algorithm = $manager->getFor($this->data->rater->data()->ageGroup, $this->data->meetings);

        $this->data->values = $this->calculateValues();
    }

    /**
     * Return the EtrPathStruct.
     *
     * @return EtrPathStruct
     */
    public function data(): EtrPathStruct
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
    private function calculateValues(): array
    {
        $flattenMeeting = $this->algorithm->flattenMeeting;
        $maxMeetings = $this->algorithm->maxMeetings;
        $centeredAt20 = $this->data->firstRating->data()->score - 20;
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
        $values[] = $this->data->firstRating->data()->score;

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