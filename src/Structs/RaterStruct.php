<?php

namespace Bluewing\Progress\Structs;

use JetBrains\PhpStorm\ArrayShape;

class RaterStruct
{
    public int|null $ageGroup = null;
    public string|null $ageGroupAsString = null;
    public int $excludeFromStats = 0;

    #[ArrayShape(['ageGroup' => "int|null", 'ageGroupAsString' => "string|null", 'excludeFromStats' => "int"])]
    public function toArray(): array
    {
        return [
            'ageGroup' => $this->ageGroup,
            'ageGroupAsString' => $this->ageGroupAsString,
            'excludeFromStats' => $this->excludeFromStats
        ];
    }
}