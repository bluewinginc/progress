<?php

namespace Bluewing\Progress\Structs;

use JetBrains\PhpStorm\ArrayShape;

class ExclusionsStruct
{
    public bool $excluded = true;
    public bool $userExcluded = false;
    public bool $firstRatingAbove32 = false;
    public bool $zeroOrOneMeetings = true;
    public bool $included = false;

    #[ArrayShape(['excluded' => "bool", 'userExcluded' => "bool", 'firstRatingAbove32' => "bool", 'zeroOrOneMeetings' => "bool", 'included' => "bool"])]
    public function toArray(): array
    {
        return [
            'excluded' => $this->excluded,
            'userExcluded' => $this->userExcluded,
            'firstRatingAbove32' => $this->firstRatingAbove32,
            'zeroOrOneMeetings' => $this->zeroOrOneMeetings,
            'included' => $this->included
        ];
    }
}