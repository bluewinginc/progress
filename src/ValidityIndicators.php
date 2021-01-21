<?php

namespace Bluewing\Progress;

use Bluewing\Algorithms2015\LongTerm\LongTermAdolescent;
use Bluewing\Algorithms2015\LongTerm\LongTermAdult;
use Bluewing\Algorithms2015\LongTerm\LongTermChild;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdult;
use Bluewing\Algorithms2015\ShortTerm\ShortTermChild;
use Bluewing\Progress\Structs\ClinicalCutoffStruct;
use Bluewing\Progress\Structs\SawtoothPatternStruct;
use Bluewing\Progress\Structs\ValidityIndicatorsStruct;

class ValidityIndicators
{
    /** @var ValidityIndicatorsStruct|null $data */
    protected $data = null;

    /** @var LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild|null $algorithm */
    protected $algorithm = null;

    /** @var RatingCollection|null $ratings */
    protected $ratings = null;

    /**
     * ValidityIndicators constructor.
     *
     * @param LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild $algorithm
     * @param RatingCollection $ratings
     */
    public function __construct($algorithm, RatingCollection $ratings)
    {
        $this->data = new ValidityIndicatorsStruct;

        $this->algorithm = $algorithm;
        $this->ratings = $ratings;

        $this->data->clinicalCutoff = $this->clinicalCutoff();
        $this->data->sawtoothPattern = $this->sawtoothPattern();
        $this->data->firstRatingAbove32 = $this->firstRatingAbove32();
        $this->data->zeroOrOneMeetings = $this->zeroOrOneMeetings();
    }

    /**
     * Return the ValidityIndicatorsStruct.
     *
     * @return ValidityIndicatorsStruct
     */
    public function data() : ValidityIndicatorsStruct
    {
        return $this->data;
    }

    /**
     * Return the ClinicalCutoffStruct.
     *
     * @return ClinicalCutoffStruct
     */
    private function clinicalCutoff() : ClinicalCutoffStruct
    {
        $cc = new ClinicalCutoff($this->algorithm, $this->ratings);
        return $cc->data();
    }

    /**
     * Determine if the first rating is above 32.
     *
     * @return bool
     */
    private function firstRatingAbove32() : bool
    {
        if ($this->ratings->count() === 0) {
            return false;
        }

        $firstRating = $this->ratings->first();

        return $firstRating->score > 32.0;
    }

    /**
     * Return the SawtoothPatternStruct.
     *
     * @return SawtoothPatternStruct
     */
    private function sawtoothPattern() : SawtoothPatternStruct
    {
        $stp = new SawtoothPattern($this->ratings);
        return $stp->data();
    }

    /**
     * Determine if there are zero or one meetings.
     *
     * @return bool
     */
    private function zeroOrOneMeetings() : bool
    {
        return ($this->ratings->count() < 2);
    }
}