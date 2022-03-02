<?php

namespace Bluewing\Progress\Structs;

use JetBrains\PhpStorm\ArrayShape;

class ClinicalCutoffStruct
{
    public float $value = 0.0;
    public float $firstRatingScore = 0.0;
    public bool $isAbove = false;

    #[ArrayShape(['value' => "float", 'firstRatingScore' => "float", 'isAbove' => "bool"])]
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'firstRatingScore' => $this->firstRatingScore,
            'isAbove' => $this->isAbove
        ];
    }
}