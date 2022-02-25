<?php

namespace Bluewing\Progress;

use Bluewing\Progress\Structs\RatingStruct;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

class RatingCollection
{
    private array $items = [];
    private bool $readOnly = false;

    /**
     * RatingCollection constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Add an item to the end of the collection.
     *
     * @param int|null $id
     * @param string|null $dateCompleted
     * @param float $score
     * @return $this
     */
    public function add(int|null $id, string|null $dateCompleted, float $score) : RatingCollection
    {
        if (! $this->readOnly) {
            $ratingStruct = new RatingStruct;

            if ($score < 0 || $score > 40) {
                throw new InvalidArgumentException('Invalid score.  It must be between 0.0 and 40.0.');
            }

            $ratingStruct->id = $id;
            $ratingStruct->dateCompleted = $dateCompleted;
            $ratingStruct->score = $score;

            $this->items[] = $ratingStruct;
        }

        return $this;
    }

    /**
     * Add an array of scores to the collection.
     *
     * @param array $scores
     * @return $this
     */
    public function addScores(array $scores) : RatingCollection
    {
        if (! $this->readOnly) {
            for ($i = 0; $i < count($scores); $i++) {
                $this->add(null, null, $scores[$i]);
            }
        }

        return $this;
    }

    /**
     * Add a RatingStruct item to the end of the collection.
     *
     * @param RatingStruct $rating
     * @return $this
     */
    public function addStruct(RatingStruct $rating) : RatingCollection
    {
        if (! $this->readOnly) {
            $this->add($rating->id, $rating->dateCompleted, $rating->score);
        }

        return $this;
    }

    /**
     * Return the number of items in the collection.
     *
     * @return int
     */
    public function count() : int
    {
        return count($this->items);
    }

    /**
     * Return the first item in the collection if it exists.
     *
     * @return RatingStruct|null
     */
    #[Pure] public function first() : ?RatingStruct
    {
        if ($this->count() > 0) {
            return $this->items[0];
        }

        return null;
    }

    /**
     * Determine if the specified index is in bounds.
     *
     * @param int $index
     * @return void
     */
    private function indexInBounds(int $index) : void
    {
        if ($this->count() === 0) {
            throw new InvalidArgumentException('The index is invalid.  There are no items in the collection.');
        }

        if ($index < 0) {
            throw new InvalidArgumentException('The index is invalid.  The lower bound of the index is 0.');
        }

        if ($index > ($this->count() - 1)) {
            throw new InvalidArgumentException('The index is invalid.  The upper bound of the index is count() - 1.');
        }
    }

    /**
     * Determine if the collection is read only.
     *
     * @return bool
     */
    public function isReadOnly() : bool
    {
        return $this->readOnly;
    }

    /**
     * Return the item at the specified index.
     *
     * @param int $index
     * @return RatingStruct
     */
    public function item(int $index) : RatingStruct
    {
        $this->indexInBounds($index);

        return $this->items[$index];
    }

    /**
     * Return the items in the collection as an array.
     *
     * @return array
     */
    public function items() : array
    {
        return $this->items;
    }

    /**
     * Return the last item in the collection if it exists.
     *
     * @return RatingStruct|null
     */
    #[Pure] public function last() : ?RatingStruct
    {
        if ($this->count() > 0) {
            $lastIndex = $this->count() - 1;

            return $this->items[$lastIndex];
        }

        return null;
    }

    /**
     * Make the collection readonly.
     * Items will not be able to be added or removed from the collection.
     *
     * @return void
     */
    public function makeReadOnly() : void
    {
        if (! $this->readOnly) {
            $this->readOnly = true;
        }
    }

    /**
     * Remove all items from the collection and return the collection.
     *
     * @return $this
     */
    public function remove() : RatingCollection
    {
        if ($this->readOnly) {
            return $this;
        }

        $this->items = [];

        return $this;
    }

    /**
     * Remove an item from the collection, at the specified index, reindex the items, and return the collection.
     *
     * @param int $index
     * @return $this
     */
    public function removeAt(int $index) : RatingCollection
    {
        if ($this->readOnly) {
            return $this;
        }

        $this->indexInBounds($index);

        unset($this->items[$index]);

        $this->items = array_values($this->items);

        return $this;
    }

    /**
     * Return the scores in the collection as an array.
     *
     * @return array
     */
    public function scores() : array
    {
        $scores = [];

        foreach($this->items as $item) {
            $scores[] = $item->score;
        }

        return $scores;
    }
}