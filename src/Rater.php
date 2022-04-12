<?php

namespace Bluewing\Progress;

use Bluewing\Progress\Structs\RaterStruct;
use InvalidArgumentException;

class Rater
{
    protected RaterStruct|null $raterStruct = null;

    public function __construct(int $ageGroup, int $excludeFromStats = 0)
    {
        if ($ageGroup < 1 || $ageGroup > 3) {
            throw new InvalidArgumentException('Invalid rater age group.  It must be set to 1, 2, or 3.');
        }

        if ($excludeFromStats < 0 || $excludeFromStats > 1) {
            throw new InvalidArgumentException('Invalid excludeFromStats value.  It must be set to 0 or 1.');
        }

        $this->raterStruct = new RaterStruct;

        $this->raterStruct->ageGroup = $ageGroup;

        if ($ageGroup === 1) {
            $this->raterStruct->ageGroupAsString = 'Adolescent';
        } elseif ($ageGroup === 2) {
            $this->raterStruct->ageGroupAsString = 'Adult';
        } elseif ($ageGroup === 3) {
            $this->raterStruct->ageGroupAsString = 'Child';
        }

        $this->raterStruct->excludeFromStats = $excludeFromStats;
    }

    /**
     * Return a raterStruct.
     *
     * @return RaterStruct
     */
    public function data(): RaterStruct
    {
        return $this->raterStruct;
    }
}