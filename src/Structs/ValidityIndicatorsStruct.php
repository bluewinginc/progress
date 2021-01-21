<?php

namespace Bluewing\Progress\Structs;

class ValidityIndicatorsStruct
{
    /** @var ClinicalCutoffStruct|null  */
    public $clinicalCutoff = null;

    /** @var bool $firstRatingAbove32 */
    public $firstRatingAbove32 = false;

    /** @var SawtoothPatternStruct|null */
    public $sawtoothPattern = null;

    /** @var bool $zeroOrOneMeetings */
    public $zeroOrOneMeetings = true;

}