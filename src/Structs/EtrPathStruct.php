<?php

namespace Bluewing\Progress\Structs;

class EtrPathStruct
{
    public RatingStruct|null $firstRating = null;
    public int $meetings = 0;
    public int $raterAgeGroup = 0;
    public array $values = [];
}