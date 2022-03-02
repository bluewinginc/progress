<?php

namespace Bluewing\Progress\Structs;

use Bluewing\Algorithms2015\LongTerm\LongTermAdolescent;
use Bluewing\Algorithms2015\LongTerm\LongTermAdult;
use Bluewing\Algorithms2015\LongTerm\LongTermChild;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdult;
use Bluewing\Algorithms2015\ShortTerm\ShortTermChild;
use Bluewing\Progress\Rater;
use Bluewing\Progress\Rating;
use Bluewing\Progress\RatingCollection;

class ProgressStruct
{
    public Rater|null $rater = null;
    public RatingCollection|null $ratings = null;
    public Rating|null $firstRating = null;
    public Rating|null $lastRating = null;
    public float $ratingChange = 0.0;
    public float|null $effectSize = null;
    public LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild|null $algorithm = null;
    public LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild|null $algorithmShortTerm = null;
    public EtrMtgTargetStruct|null $etrMtgTarget = null;
    public EtrTargetStruct|null $etrTarget = null;
    public MilestonesStruct|null $milestones = null;
    public ValidityIndicatorsStruct|null $validityIndicators = null;
    public ExclusionsStruct|null $exclusions = null;

    /**
     * Return the data flattened as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'rater' => $this->rater->data()->toArray(),
            'ratingsCount' => $this->ratings->count(),
            'ratings' => $this->ratings->items(true),
            'firstRating' => $this->firstRating->data()->toArray(),
            'lastRating' => $this->lastRating->data()->toArray(),
            'ratingChange' => $this->ratingChange,
            'effectSize' => $this->effectSize,
            'algorithm' => $this->algorithm->toArray(),
            'algorithmShortTerm' => $this->algorithmShortTerm->toArray(),
            'etrMtgTarget' => $this->etrMtgTarget->toArray(),
            'etrTarget' => $this->etrTarget->toArray(),
            'milestones' => $this->milestones->toArray(),
            'validityIndicators' => $this->validityIndicators->toArray(),
            'exclusions' => $this->exclusions->toArray()
        ];
    }

    /**
     * Return the data as JSON.
     *
     * @return false|string
     */
    public function toJson(): false|string
    {
        return json_encode($this->toArray());
    }
}