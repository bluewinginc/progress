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

        // Get the expected treatment response (etr) for each meeting.
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
        $value = $this->data->firstRating->data()->score;
        $this->data->values[] = $value;
        $this->data->valuesAsString[] = number_format($value, 1);

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

            $roundedValue = round($value, 1, PHP_ROUND_HALF_UP);
            $this->data->values[] = $roundedValue;
            $this->data->valuesAsString[] = number_format($roundedValue, 1);
        }
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
}