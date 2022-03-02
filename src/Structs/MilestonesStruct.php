<?php

namespace Bluewing\Progress\Structs;

use JetBrains\PhpStorm\ArrayShape;

class MilestonesStruct
{
    public bool $cscMet = false;
    public bool $rcMet = false;
    public bool $rcOrCscMet = false;

    #[ArrayShape(['cscMet' => "bool", 'rcMet' => "bool", 'rcOrCscMet' => "bool"])]
    public function toArray(): array
    {
        return [
            'cscMet' => $this->cscMet,
            'rcMet' => $this->rcMet,
            'rcOrCscMet' => $this->rcOrCscMet
        ];
    }
}