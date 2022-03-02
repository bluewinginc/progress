<?php

namespace Bluewing\Progress\Structs;

use JetBrains\PhpStorm\ArrayShape;

class RaterStruct
{
    public int $ageGroup = 0; //AgeGroup::None;
    public int $excludeFromStats = 0;

    #[ArrayShape(['ageGroup' => "int", 'excludeFromStats' => "int"])]
    public function toArray(): array
    {
        return [
            'ageGroup' => $this->ageGroup,
            'excludeFromStats' => $this->excludeFromStats
        ];
    }
}