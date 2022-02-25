<?php

namespace Bluewing\Progress\Structs;

use Bluewing\Algorithms2015\LongTerm\LongTermAdolescent;
use Bluewing\Algorithms2015\LongTerm\LongTermAdult;
use Bluewing\Algorithms2015\LongTerm\LongTermChild;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdult;
use Bluewing\Algorithms2015\ShortTerm\ShortTermChild;
use Bluewing\Progress\RatingCollection;

class ProgressStruct
{
    public RaterStruct|null $rater = null;
    public bool $userExcluded = false;
    public RatingCollection|null $ratings = null;
    public RatingStruct|null $firstRating = null;
    public RatingStruct|null $lastRating = null;
    public float $ratingChange = 0.0;
    public float|null $effectSize = null;
    public LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild|null $algorithm = null;
    public LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild|null $algorithmShortTerm = null;
    public EtrMtgTargetStruct|null $etrMtgTarget = null;
    public EtrTargetStruct|null $etrTarget = null;
    public MilestonesStruct|null $milestones = null;
    public ValidityIndicatorsStruct|null $validityIndicators = null;
    public ExclusionsStruct|null $exclusions = null;
}