<?php

namespace Bluewing\Progress\Structs;

use JetBrains\PhpStorm\ArrayShape;

class RatingStruct
{
    public int|null $id = null;
    public string|null $dateCompleted = null;
    public float $score = 0.0;

    /**
     * Return the RatingStruct as an array.
     *
     * @return array
     */
    #[ArrayShape(['id' => "int|null", 'dateCompleted' => "null|string", 'score' => "float"])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'dateCompleted' => $this->dateCompleted,
            'score' => $this->score
        ];
    }
}