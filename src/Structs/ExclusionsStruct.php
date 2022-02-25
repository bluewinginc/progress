<?php

namespace Bluewing\Progress\Structs;

class ExclusionsStruct
{
    public bool $excluded = true;
    public bool $userExcluded = false;
    public bool $firstRatingAbove32 = false;
    public bool $zeroOrOneMeetings = true;
    public bool $included = false;
}