<?php

namespace Bluewing\Progress\Structs;

use Bluewing\Progress\Rater;
use Bluewing\Progress\Rating;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class EtrPathStruct
{
    public Rater|null $rater = null;
    public Rating|null $firstRating = null;
    public int $meetings = 0;
    public array $values = [];
    public array $valuesAsString = [];

    #[Pure] #[ArrayShape(['rater' => "array", 'firstRating' => "array", 'meetings' => "int", 'values' => "array", 'valuesAsString' => "array"])]
    public function toArray(): array
    {
        return [
            'rater' => $this->rater->data()->toArray(),
            'firstRating' => $this->firstRating->data()->toArray(),
            'meetings' => $this->meetings,
            'values' => $this->values,
            'valuesAsString' => $this->valuesAsString
        ];
    }
}