<?php

namespace Bluewing\Progress\Structs;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class ValidityIndicatorsStruct
{
    public ClinicalCutoffStruct|null $clinicalCutoff = null;
    public bool $firstRatingAbove32 = false;
    public SawtoothPatternStruct|null $sawtoothPattern = null;
    public bool $zeroOrOneMeetings = true;

    #[Pure] #[ArrayShape(['clinicalCutoff' => "array", 'firstRatingAbove32' => "bool", 'sawtoothPattern' => "array", 'zeroOrOneMeetings' => "bool"])]
    public function toArray(): array
    {
        return [
            'clinicalCutoff' => $this->clinicalCutoff->toArray(),
            'firstRatingAbove32' => $this->firstRatingAbove32,
            'sawtoothPattern' => $this->sawtoothPattern->toArray(),
            'zeroOrOneMeetings' => $this->zeroOrOneMeetings
        ];
    }
}