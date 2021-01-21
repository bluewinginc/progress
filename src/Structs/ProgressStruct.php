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
    /** @var RaterStruct|null $rater */
    public $rater = null;

    /** @var bool $userExcluded */
    public $userExcluded = false;

    /** @var RatingCollection|null $ratings */
    public $ratings = null;

    /** @var RatingStruct|null $firstRating */
    public $firstRating = null;

    /** @var RatingStruct|null $lastRating */
    public $lastRating = null;

    /** @var float $ratingChange  */
    public $ratingChange = 0.0;

    /** @var null $effectSize */
    public $effectSize = null;

    /** @var LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild|null $algorithm */
    public $algorithm = null;

    /** @var LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild|null $algorithmShortTerm  */
    public $algorithmShortTerm = null;

    /** @var EtrMtgTargetStruct|null $etrMtgTargetStruct  */
    public $etrMtgTarget = null;

    /** @var EtrTargetStruct|null $etrTarget */
    public $etrTarget = null;

    /** @var MilestonesStruct|null $milestones  */
    public $milestones = null;

    /** @var ValidityIndicatorsStruct|null $validityIndicators */
    public $validityIndicators = null;

    /** @var ExclusionsStruct|null $exclusions */
    public $exclusions = null;
}