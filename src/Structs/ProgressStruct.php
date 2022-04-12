<?php

namespace Bluewing\Progress\Structs;

use Bluewing\Progress\Rater;
use Bluewing\Progress\Rating;
use Bluewing\Progress\RatingCollection;
use JetBrains\PhpStorm\Pure;

class ProgressStruct
{
    public Rater|null $rater = null;
    public int $ratingsCount = 0;
    public RatingCollection|null $ratings = null;
    public Rating|null $firstRating = null;
    public Rating|null $lastRating = null;
    public float $ratingChange = 0.0;
    public string $ratingChangeAsString = '0.0';
    public float|null $effectSize = null;
    public string|null $effectSizeAsString = null;
    public AlgorithmStruct|null $algorithm = null;
    public AlgorithmStruct|null $algorithmShortTerm = null;
    public EtrStruct|null $etrMtgTarget = null;
    public EtrStruct|null $etrTarget = null;
    public MilestonesStruct|null $milestones = null;
    public ValidityIndicatorsStruct|null $validityIndicators = null;
    public ExclusionsStruct|null $exclusions = null;

    /**
     * Return the data flattened as an array.
     *
     * @param bool $justScores
     * @return array
     */
    #[Pure] public function toArray(bool $justScores = false): array
    {
        return [
            'rater' => $this->rater->data()->toArray(),
            'ratingsCount' => $this->ratingsCount,
            'ratings' => $this->ratings->items(true, $justScores),
            'firstRating' => $this->firstRating->data()->toArray($justScores),
            'lastRating' => $this->lastRating->data()->toArray($justScores),
            'ratingChange' => $this->ratingChange,
            'ratingChangeAsString' => $this->ratingChangeAsString,
            'effectSize' => $this->effectSize,
            'effectSizeAsString' => $this->effectSizeAsString,
            'algorithm' => $this->algorithm->toArray(),
            'algorithmShortTerm' => $this->algorithmShortTerm->toArray(),
            'etrMtgTarget' => $this->etrMtgTarget->toArray(),
            'etrTarget' => $this->etrTarget->toArray(),
            'milestones' => $this->milestones->toArray(),
            'validityIndicators' => $this->validityIndicators->toArray(),
            'exclusions' => $this->exclusions->toArray()
        ];
    }
}