<?php

namespace Bluewing\Progress\Structs;

class ExclusionsStruct
{
    /** @var bool $excluded */
    public $excluded = true;

    /** @var bool $userExcluded */
    public $userExcluded = false;

    /** @var bool $firstRatingAbove32 */
    public $firstRatingAbove32 = false;

    /** @var bool $zeroOrOneMeetings */
    public $zeroOrOneMeetings = true;

    /** @var bool $included */
    public $included = false;
}