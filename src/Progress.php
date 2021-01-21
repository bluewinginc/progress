<?php

namespace Bluewing\Progress;

use Bluewing\Algorithms2015\AlgorithmManager;
use Bluewing\Progress\Structs\ProgressStruct;
use Bluewing\Progress\Structs\RaterStruct;
use Bluewing\Progress\Structs\RatingStruct;
use Exception;
use InvalidArgumentException;

class Progress
{
    /** @var ProgressStruct|null $progress */
    protected $progress = null;

    /**
     * Progress constructor.
     *
     * @param RaterStruct $rater
     * @param RatingCollection $ratings
     * @throws Exception
     */
    public function __construct(RaterStruct $rater, RatingCollection $ratings)
    {
        $this->progress = new ProgressStruct;

        $this->progress->rater = $rater;
        $this->progress->ratings = $ratings;

        $this->progress->userExcluded = $this->progress->rater->excludeFromStats;

        $this->progress->firstRating = $this->progress->ratings->first();

        if (is_null($this->progress->firstRating)) {
            $this->progress->firstRating = new RatingStruct;
        }

        if ($this->progress->firstRating->score < 0 || $this->progress->firstRating->score > 40) {
            throw new InvalidArgumentException('The first rating score is invalid. It must be between 0.0 and 40.0.');
        }

        $this->progress->lastRating = $this->progress->ratings->last();

        if (is_null($this->progress->lastRating)) {
            $this->progress->lastRating = new RatingStruct;
        }

        if ($this->progress->lastRating->score < 0 || $this->progress->lastRating->score > 40) {
            throw new InvalidArgumentException('The last rating score is invalid. It must be between 0.0 and 40.0.');
        }

        // Only update the rating change value if there is one or more ratings.
        if ($this->progress->ratings->count() > 0) {
            $this->progress->ratingChange = (float)($this->progress->lastRating->score - $this->progress->firstRating->score);
        }

        // Algorithms
        $manager = new AlgorithmManager;

        $this->progress->algorithm = $manager->getFor($this->progress->rater->ageGroup, $this->progress->ratings->count());
        $this->progress->algorithmShortTerm = $manager->getFor($this->progress->rater->ageGroup, 0);

        $this->progress->effectSize = ($this->progress->ratingChange / $this->progress->algorithm->standardDeviation);

        // ETR Meeting Target
        $etrMtgTarget = new EtrMtgTarget($this->progress->rater->ageGroup, $this->progress->ratings);
        $this->progress->etrMtgTarget = $etrMtgTarget->data();

        // ETR Target
        $etrTarget = new EtrTarget($this->progress->rater->ageGroup, $this->progress->ratings);
        $this->progress->etrTarget = $etrTarget->data();

        // Validity Indicators
        $validityIndicators = new ValidityIndicators($this->progress->algorithm, $this->progress->ratings);
        $this->progress->validityIndicators = $validityIndicators->data();

        // Milestones
        $milestones = new Milestones($this->progress->algorithm, $this->progress->ratings);
        $this->progress->milestones = $milestones->data();

        // Exclusions
        $exclusions = new Exclusions($this->progress->userExcluded, $this->progress->validityIndicators->firstRatingAbove32, $this->progress->validityIndicators->zeroOrOneMeetings);
        $this->progress->exclusions = $exclusions->data();
    }

    /**
     * Return the data as a ProgressStruct.
     *
     * @return ProgressStruct
     */
    public function data() : ProgressStruct
    {
        return $this->progress;
    }

    /**
     * Return the data flattened as an array.
     *
     * @return array
     */
    public function flatten() : array
    {
        return [
            'rater.excludeFromStats' => $this->progress->rater->excludeFromStats,
            'rater.ageGroup' => $this->progress->rater->ageGroup,

            'ratings.count' => $this->progress->ratings->count(),

            'firstRating.score' => $this->progress->firstRating->score,
            'firstRating.dateCompleted' => $this->progress->firstRating->dateCompleted,

            'lastRating.score' => $this->progress->lastRating->score,
            'lastRating.dateCompleted' => $this->progress->lastRating->dateCompleted,

            'ratingChange' => $this->progress->ratingChange,
            'effectSize' => $this->progress->effectSize,

            'algorithm.info' => $this->progress->algorithm->info,
            'algorithm.clinicalCutoff' => $this->progress->algorithm->clinicalCutoff,
            'algorithm.minMeetings' => $this->progress->algorithm->minMeetings,
            'algorithm.maxMeetings' => $this->progress->algorithm->maxMeetings,
            'algorithm.flattenMeeting' => $this->progress->algorithm->flattenMeeting,
            'algorithm.interceptMean' => $this->progress->algorithm->interceptMean,
            'algorithm.linearMean' => $this->progress->algorithm->linearMean,
            'algorithm.quadraticMean' => $this->progress->algorithm->quadraticMean,
            'algorithm.cubicMean' => $this->progress->algorithm->cubicMean,
            'algorithm.intake' => $this->progress->algorithm->intake,
            'algorithm.linearByIntake' => $this->progress->algorithm->linearByIntake,
            'algorithm.quadraticByIntake' => $this->progress->algorithm->quadraticByIntake,
            'algorithm.cubicByIntake' => $this->progress->algorithm->cubicByIntake,
            'algorithm.reliableChangeIndex' => $this->progress->algorithm->reliableChangeIndex,
            'algorithm.standardDeviation' => $this->progress->algorithm->standardDeviation,

            'algorithmShortTerm.info' => $this->progress->algorithmShortTerm->info,
            'algorithmShortTerm.clinicalCutoff' => $this->progress->algorithmShortTerm->clinicalCutoff,
            'algorithmShortTerm.minMeetings' => $this->progress->algorithmShortTerm->minMeetings,
            'algorithmShortTerm.maxMeetings' => $this->progress->algorithmShortTerm->maxMeetings,
            'algorithmShortTerm.flattenMeeting' => $this->progress->algorithmShortTerm->flattenMeeting,
            'algorithmShortTerm.interceptMean' => $this->progress->algorithmShortTerm->interceptMean,
            'algorithmShortTerm.linearMean' => $this->progress->algorithmShortTerm->linearMean,
            'algorithmShortTerm.quadraticMean' => $this->progress->algorithmShortTerm->quadraticMean,
            'algorithmShortTerm.cubicMean' => $this->progress->algorithmShortTerm->cubicMean,
            'algorithmShortTerm.intake' => $this->progress->algorithmShortTerm->intake,
            'algorithmShortTerm.linearByIntake' => $this->progress->algorithmShortTerm->linearByIntake,
            'algorithmShortTerm.quadraticByIntake' => $this->progress->algorithmShortTerm->quadraticByIntake,
            'algorithmShortTerm.cubicByIntake' => $this->progress->algorithmShortTerm->cubicByIntake,
            'algorithmShortTerm.reliableChangeIndex' => $this->progress->algorithmShortTerm->reliableChangeIndex,
            'algorithmShortTerm.standardDeviation' => $this->progress->algorithmShortTerm->standardDeviation,

            'etrMtgTarget.expectedChange' => $this->progress->etrMtgTarget->expectedChange,
            'etrMtgTarget.met' => $this->progress->etrMtgTarget->met,
            'etrMtgTarget.metPercent' => $this->progress->etrMtgTarget->metPercent,
            'etrMtgTarget.metPercent50' => $this->progress->etrMtgTarget->metPercent50,
            'etrMtgTarget.metPercent67' => $this->progress->etrMtgTarget->metPercent67,
            'etrMtgTarget.value' => $this->progress->etrMtgTarget->value,

            'etrTarget.expectedChange' => $this->progress->etrTarget->expectedChange,
            'etrTarget.met' => $this->progress->etrTarget->met,
            'etrTarget.metPercent' => $this->progress->etrTarget->metPercent,
            'etrTarget.metPercent50' => $this->progress->etrTarget->metPercent50,
            'etrTarget.metPercent67' => $this->progress->etrTarget->metPercent67,
            'etrTarget.value' => $this->progress->etrTarget->value,

            'milestones.cscMet' => $this->progress->milestones->cscMet,
            'milestones.rcMet' => $this->progress->milestones->rcMet,
            'milestones.rcOrCscMet' => $this->progress->milestones->rcOrCscMet,

            'validityIndicators.clinicalCutoff.value' => $this->progress->validityIndicators->clinicalCutoff->value,
            'validityIndicators.clinicalCutoff.firstRatingScore' => $this->progress->validityIndicators->clinicalCutoff->firstRatingScore,
            'validityIndicators.clinicalCutoff.isAbove' => $this->progress->validityIndicators->clinicalCutoff->isAbove,
            'validityIndicators.firstRatingAbove32' => $this->progress->validityIndicators->firstRatingAbove32,
            'validityIndicators.sawtoothPattern.directionChanges' => $this->progress->validityIndicators->sawtoothPattern->directionChanges,
            'validityIndicators.sawtoothPattern.has' => $this->progress->validityIndicators->sawtoothPattern->has,
            'validityIndicators.sawtoothPattern.teeth' => $this->progress->validityIndicators->sawtoothPattern->teeth,
            'validityIndicators.zeroOrOneMeetings' => $this->progress->validityIndicators->zeroOrOneMeetings,

            'exclusions.excluded' => $this->progress->exclusions->excluded,
            'exclusions.userExcluded' => $this->progress->exclusions->userExcluded,
            'exclusions.firstRatingAbove32' => $this->progress->exclusions->firstRatingAbove32,
            'exclusions.zeroOrOneMeetings' => $this->progress->exclusions->zeroOrOneMeetings,
            'exclusions.included' => $this->progress->exclusions->included
        ];
    }
}