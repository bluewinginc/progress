<?php

namespace Bluewing\Progress\Structs;

use JetBrains\PhpStorm\ArrayShape;

class SawtoothPatternStruct
{
    public int $directionChanges = 0;
    public bool $has = false;
    public int $teeth = 0;

    #[ArrayShape(['directionChanges' => "int", 'has' => "bool", 'teeth' => "int"])]
    public function toArray(): array
    {
        return [
            'directionChanges' => $this->directionChanges,
            'has' => $this->has,
            'teeth' => $this->teeth
        ];
    }
}