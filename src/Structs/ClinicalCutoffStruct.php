<?php

namespace Bluewing\Progress\Structs;

use JetBrains\PhpStorm\ArrayShape;

class ClinicalCutoffStruct
{
    public float $value = 0.0;
    public string $valueAsString = '0.0';
    public float|null $firstRatingScore = null;
    public string|null $firstRatingScoreAsString = null;
    public bool $isAbove = false;

    #[ArrayShape(['value' => "float", 'valueAsString' => "string", 'firstRatingScore' => "float|null", 'firstRatingScoreAsString' => "string|null", 'isAbove' => "bool"])]
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'valueAsString' => $this->valueAsString,
            'firstRatingScore' => $this->firstRatingScore,
            'firstRatingScoreAsString' => $this->firstRatingScoreAsString,
            'isAbove' => $this->isAbove
        ];
    }
}