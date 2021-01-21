<?php

namespace Bluewing\Progress\Structs;

class EtrPathStruct
{
    /** @var RatingStruct|null $firstRating */
    public $firstRating = null;

    /** @var int $meetings */
    public $meetings = 0;

    /** @var int $raterAgeGroup */
    public $raterAgeGroup = 0;

    /** @var array $values */
    public $values = [];
}