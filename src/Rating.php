<?php

namespace Bluewing\Progress;

use Bluewing\Progress\Structs\RatingStruct;
use InvalidArgumentException;

class Rating
{
    protected RatingStruct|null $ratingStruct = null;

    public function __construct(int|null $id = null, string|null $dateCompleted = null, float $score = 0.0)
    {
        $this->ratingStruct = new RatingStruct;

        $this->ratingStruct->id = $id;
        $this->ratingStruct->dateCompleted = $dateCompleted;
        $this->ratingStruct->score = $score;
        $this->ratingStruct->scoreAsString = number_format($score, 1);

        $this->check();
    }

    /**
     * Checks to see if the RatingStruct is valid.
     *
     * @throws InvalidArgumentException
     */
    private function check(): void
    {
        if ($this->ratingStruct->score < 0 || $this->ratingStruct->score > 40) {
            throw new InvalidArgumentException('Invalid score.  It must be between 0.0 and 40.0.');
        }
    }

    /**
     * Return a ratingStruct.
     *
     * @return RatingStruct
     */
    public function data(): RatingStruct
    {
        return $this->ratingStruct;
    }

    /**
     * Populates the rating struct from an array of data.
     * The array must contain valid data, or it will fail.
     *
     * @param array $data
     * @return void
     */
    public function fromArray(array $data): void
    {
        if (! array_key_exists('id', $data)) throw new InvalidArgumentException('The id key is required.');

        if (! array_key_exists('dateCompleted', $data)) throw new InvalidArgumentException('The dateCompleted key is required.');

        if (! array_key_exists('score', $data)) throw new InvalidArgumentException('The score key is required.');

        $id = $data['id'];
        $dateCompleted = $data['dateCompleted'];
        $score = $data['score'];

        $this->ratingStruct->id = is_int($id) ? $id : null;
        $this->ratingStruct->dateCompleted = is_string($dateCompleted) ? $dateCompleted : null;
        $this->ratingStruct->score = is_float($score) ? $score : -1;
        $this->ratingStruct->scoreAsString = number_format($score , 1);

        $this->check();
    }
}