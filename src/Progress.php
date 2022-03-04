<?php

namespace Bluewing\Progress;

use Bluewing\Algorithms2015\AlgorithmManager;
use Bluewing\Progress\Structs\AlgorithmStruct;
use Bluewing\Progress\Structs\ProgressStruct;
use InvalidArgumentException;

class Progress
{
    protected ProgressStruct|null $progress = null;
    protected Rater|null $rater = null;
    protected RatingCollection|null $ratings = null;

    /**
     * Progress constructor.
     *
     * @param Rater $rater
     * @param RatingCollection $ratings
     */
    public function __construct(Rater $rater, RatingCollection $ratings)
    {
        $this->progress = new ProgressStruct;

        $this->rater = $rater;
        $this->ratings = $ratings;

        $this->progress->rater = $rater;
        $this->progress->ratings = $ratings;

        $this->progress->ratingsCount = $ratings->count();

        $this->progress->firstRating = $this->progress->ratings->first();

        if (is_null($this->progress->firstRating)) {
            $this->progress->firstRating = null;
        }

        if ($this->progress->firstRating->data()->score < 0 || $this->progress->firstRating->data()->score > 40) {
            throw new InvalidArgumentException('The first rating score is invalid. It must be between 0.0 and 40.0.');
        }

        $this->progress->lastRating = $this->progress->ratings->last();

        if (is_null($this->progress->lastRating)) {
            $this->progress->lastRating = null;
        }

        if ($this->progress->lastRating->data()->score < 0 || $this->progress->lastRating->data()->score > 40) {
            throw new InvalidArgumentException('The last rating score is invalid. It must be between 0.0 and 40.0.');
        }

        // Only update the rating change value if there is one or more ratings.
        if ($this->progress->ratings->count() > 0) {
            $this->progress->ratingChange = round($this->progress->lastRating->data()->score - $this->progress->firstRating->data()->score, 1);
            $this->progress->ratingChangeAsString = number_format($this->progress->ratingChange, 1);
        }

        // Algorithms
        $manager = new AlgorithmManager;

        $algorithm = $manager->getFor($this->progress->rater->data()->ageGroup, $this->progress->ratings->count());
        $algorithmShortTerm = $manager->getFor($this->progress->rater->data()->ageGroup, 0);

        // algorithm
        $algorithmStruct = new AlgorithmStruct;
        $algorithmStruct->version = $algorithm->info;
        $algorithmStruct->clinicalCutoff = $algorithm->clinicalCutoff;
        $algorithmStruct->clinicalCutoffAsString = number_format($algorithm->clinicalCutoff, 1);
        $algorithmStruct->reliableChangeIndex = $algorithm->reliableChangeIndex;
        $algorithmStruct->reliableChangeIndexAsString = number_format($algorithm->reliableChangeIndex, 2);
        $algorithmStruct->standardDeviation = $algorithm->standardDeviation;
        $algorithmStruct->standardDeviationAsString = number_format($algorithm->standardDeviation, 2);
        $algorithmStruct->srsClinicalCutoff = 36;
        $algorithmStruct->srsClinicalCutoffAsString = number_format(36, 1);
        $this->progress->algorithm = $algorithmStruct;

        // algorithmShortTerm
        $algorithmStruct = new AlgorithmStruct;
        $algorithmStruct->version = $algorithmShortTerm->info;
        $algorithmStruct->clinicalCutoff = $algorithmShortTerm->clinicalCutoff;
        $algorithmStruct->clinicalCutoffAsString = number_format($algorithmShortTerm->clinicalCutoff, 1);
        $algorithmStruct->reliableChangeIndex = $algorithmShortTerm->reliableChangeIndex;
        $algorithmStruct->reliableChangeIndexAsString = number_format($algorithmShortTerm->reliableChangeIndex, 2);
        $algorithmStruct->standardDeviation = $algorithmShortTerm->standardDeviation;
        $algorithmStruct->standardDeviationAsString = number_format($algorithmShortTerm->standardDeviation, 2);
        $algorithmStruct->srsClinicalCutoff = 36;
        $algorithmStruct->srsClinicalCutoffAsString = number_format(36, 1);
        $this->progress->algorithmShortTerm = $algorithmStruct;

        $this->progress->effectSize = round($this->progress->ratingChange / $algorithm->standardDeviation, 2);
        $this->progress->effectSizeAsString = number_format($this->progress->effectSize, 2);

        // ETR Meeting Target
        $etrMtgTarget = new EtrMtgTarget($this->progress->rater, $this->progress->ratings);
        $this->progress->etrMtgTarget = $etrMtgTarget->data();

        // ETR Target
        $etrTarget = new EtrTarget($this->progress->rater, $this->progress->ratings);
        $this->progress->etrTarget = $etrTarget->data();

        // Validity Indicators
        $validityIndicators = new ValidityIndicators($algorithm, $this->progress->ratings);
        $this->progress->validityIndicators = $validityIndicators->data();

        // Milestones
        $milestones = new Milestones($algorithm, $this->progress->ratings);
        $this->progress->milestones = $milestones->data();

        // Exclusions
        $exclusions = new Exclusions($this->progress->rater->data()->excludeFromStats === 1, $this->progress->validityIndicators->firstRatingAbove32, $this->progress->validityIndicators->zeroOrOneMeetings);
        $this->progress->exclusions = $exclusions->data();
    }

    /**
     * Return the data as a ProgressStruct.
     *
     * @return ProgressStruct
     */
    public function data(): ProgressStruct
    {
        return $this->progress;
    }
}