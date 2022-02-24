<?php

namespace Bluewing\Progress\Structs;

class ValidityIndicatorsStruct
{
    public ClinicalCutoffStruct|null $clinicalCutoff = null;
    public bool $firstRatingAbove32 = false;
    public SawtoothPatternStruct|null $sawtoothPattern = null;
    public bool $zeroOrOneMeetings = true;
}