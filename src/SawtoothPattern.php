<?php

namespace Bluewing\Progress;

use Bluewing\Progress\Structs\SawtoothPatternStruct;
use JetBrains\PhpStorm\Pure;

class SawtoothPattern
{
    protected SawtoothPatternStruct|null $data = null;
    protected RatingCollection|null $ratings = null;

    const DIRECTION_CHANGES = 4;
    const POINT_CHANGE = 6;

    /**
     * SawtoothPattern constructor.
     *
     * @param RatingCollection $ratings
     */
    public function __construct(RatingCollection $ratings)
    {
        $this->data = new SawtoothPatternStruct;

        $this->ratings = $ratings;

        $this->data->directionChanges = $this->calculateSawtoothDirectionChanges();
        $this->data->has = $this->has();
        $this->data->teeth = $this->teeth();
    }

    /**
     * Return the SawtoothPatternStruct.
     *
     * @return SawtoothPatternStruct
     */
    public function data() : SawtoothPatternStruct
    {
        return $this->data;
    }

    /**
     * Return the number of alternating changes, using the POINT_CHANGE, in the rating scores.
     *
     * @return int
     */
    private function calculateSawtoothDirectionChanges() : int
    {
        $directionChanges = 0;

        // If there are less than 2 ratings, there can't be any direction changes.
        if ($this->ratings->count() < 2) {
            return 0;
        }

        // 2019-03-12 - LOGIC CHANGE
        // The objective is to flag four (4) consecutive or non-consecutive direction changes of six (6) points or more.
        // 1.0; 7.0; 2.0; 6.0; 12.0; 18.0; 26.0; 15.0; 9.0;
        //   [1]                              [2]

        $direction = 'none';

        for ($i = 0; $i < ($this->ratings->count() - 1); $i++) {
            $value1 = ($this->ratings->item($i)->score * 10)/10;
            $value2 = ($this->ratings->item($i + 1)->score * 10)/10;
            $diff = abs($value2 - $value1);

            if ($diff >= $this::POINT_CHANGE) {
                if ($value2 > $value1) {
                    if ($direction === 'down' || $direction === 'none') {
                        $directionChanges += 1;
                    }
                    $direction = 'up';
                } else {
                    if ($direction === 'up' || $direction === 'none') {
                        $directionChanges += 1;
                    }
                    $direction = 'down';
                }
            }
        }

        return $directionChanges;
    }

    /**
     * Determine if a rater has a sawtooth pattern, using the DIRECTION_CHANGES constant.
     * When the number of ratings is less than the DIRECTION_CHANGES + 1, return FALSE.
     *
     * @return bool
     */
    #[Pure] private function has() : bool
    {
        if ($this->ratings->count() < $this::DIRECTION_CHANGES + 1) {
            return false;
        }

        return ($this->data->directionChanges >= $this::DIRECTION_CHANGES);
    }

    /**
     * Return the number of teeth.
     * There must be at least one direction change, or it is automatically 0.
     *
     * @return int
     */
    private function teeth() : int
    {
        if ($this->data->directionChanges < 1) {
            return 0;
        }

        return $this->data->directionChanges - 1;
    }
}