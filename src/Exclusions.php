<?php

namespace Bluewing\Progress;

use Bluewing\Progress\Structs\ExclusionsStruct;
use JetBrains\PhpStorm\Pure;

class Exclusions
{
    protected ExclusionsStruct|null $data = null;

    /**
     * Exclusions constructor.
     *
     * @param bool $userExcluded
     * @param bool $firstRatingAbove32
     * @param bool $zeroOrOneMeetings
     */
    #[Pure] public function __construct(bool $userExcluded, bool $firstRatingAbove32, bool $zeroOrOneMeetings)
    {
        $this->data = new ExclusionsStruct;

        $this->data->userExcluded = $userExcluded;
        $this->data->firstRatingAbove32 = $firstRatingAbove32;
        $this->data->zeroOrOneMeetings = $zeroOrOneMeetings;

        $this->data->excluded = $userExcluded || $firstRatingAbove32 || $zeroOrOneMeetings;
        $this->data->included = !$this->data->excluded;
    }

    /**
     * Return the ExclusionsStruct.
     *
     * @return ExclusionsStruct
     */
    public function data(): ExclusionsStruct
    {
        return $this->data;
    }
}