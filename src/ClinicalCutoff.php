<?php

namespace Bluewing\Progress;

use Bluewing\Algorithms2015\LongTerm\LongTermAdolescent;
use Bluewing\Algorithms2015\LongTerm\LongTermAdult;
use Bluewing\Algorithms2015\LongTerm\LongTermChild;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent;
use Bluewing\Algorithms2015\ShortTerm\ShortTermAdult;
use Bluewing\Algorithms2015\ShortTerm\ShortTermChild;
use Bluewing\Progress\Structs\ClinicalCutoffStruct;
use InvalidArgumentException;

class ClinicalCutoff
{
    private ClinicalCutoffStruct|null $data;
    private LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild $algorithm;
    private RatingCollection|null $ratings;

    /**
     * ClinicalCutoff constructor.
     *
     * @param LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild $algorithm
     * @param RatingCollection $ratings
     */
    public function __construct(LongTermAdolescent|LongTermAdult|LongTermChild|ShortTermAdolescent|ShortTermAdult|ShortTermChild $algorithm, RatingCollection $ratings)
    {
        $this->data = new ClinicalCutoffStruct;

        $this->algorithm = $algorithm;
        $this->ratings = $ratings;

        $this->data->value = $this->algorithm->clinicalCutoff;
        $this->data->valueAsString = number_format($this->data->value, 1);

        $this->calculateAndPopulateData();
    }

    /**
     * Calculate the data.
     *
     * @return void
     */
    private function calculateAndPopulateData(): void
    {
        $firstRating = $this->ratings->first();

        if (is_null($firstRating)) {
            $this->data->firstRatingScore = null;
            $this->data->firstRatingScoreAsString = null;
            $this->data->isAbove = false;
            return;
        }

        if ($firstRating->data()->score < 0 || $firstRating->data()->score > 40) {
            throw new InvalidArgumentException('The first rating score is invalid. It must be between 0.0 and 40.0.');
        }

        $this->data->firstRatingScore = $firstRating->data()->score;
        $this->data->firstRatingScoreAsString = number_format($this->data->firstRatingScore, 1);
        $this->data->isAbove = ($firstRating->data()->score > $this->data->value);
    }

    /**
     * Return the ClinicalCutoffStruct.
     *
     * @return ClinicalCutoffStruct
     */
    public function data(): ClinicalCutoffStruct
    {
        return $this->data;
    }
}