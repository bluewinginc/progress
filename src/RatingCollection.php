<?php

namespace Bluewing\Progress;

use Bluewing\Progress\Structs\RatingStruct;

class RatingCollection
{
    /** @var int $count */
    private $count = 0;

    /** @var array $items */
    private $items = [];

    /** @var bool $readOnly */
    private $readOnly = false;

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
     * @param int $id
     * @param string $dateCompleted
     * @param float $score
     * @return RatingCollection
     */
    public function add(int $id, string $dateCompleted, float $score) : RatingCollection
    {
        if (! $this->readOnly) {
            $ratingStruct = new RatingStruct;

            $ratingStruct->id = $id;
            $ratingStruct->dateCompleted = $dateCompleted;
            $ratingStruct->score = $score;

            $this->items[] = $ratingStruct;
            $this->count = count($this->items);
        }

        return $this;
    }

    /**
     * Add array items to the collection.
     *
     * @param array $ratingsArray
     * @return RatingCollection
     */
    public function addFromArray(array $ratingsArray) : RatingCollection
    {
        $count = count($ratingsArray);

        if ($count > 0) {
            if (! $this->readOnly) {
                for ($i = 0; $i < $count; $i++) {
                    $rating = new RatingStruct;

                    $rating->id = 0;
                    $rating->score = $ratingsArray[$i];
                    $rating->dateCompleted = null;

                    $this->items[] = $rating;
                }

                $this->count = count($this->items);
            }
        }

        return $this;
    }

    /**
     * Add an item to the end of the collection.
     *
     * @param RatingStruct $rating
     * @return RatingCollection
     */
    public function addFromStruct(RatingStruct $rating) : RatingCollection
    {
        if (! $this->readOnly) {
            $this->items[] = $rating;
            $this->count = count($this->items);
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
    public function first() : ?RatingStruct
    {
        if ($this->count() > 0) {
            return $this->items[0];
        }

        return null;
    }

    /**
     * Determine if the specified index contains an item.
     *
     * @param int $index
     * @return bool
     */
    private function indexContainsItem(int $index) : bool
    {
        // The index must be 0 or greater than 0.
        if ($index < 0) {
            return false;
        }

        // The index cannot be equal to the count or be greater than the count.
        if ($index >= $this->count()) {
            return false;
        }

        return true;
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
    public function last() : ?RatingStruct
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
     */
    public function makeReadOnly()
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
        $this->count = count($this->items);

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

        if (! $this->indexContainsItem($index)) {
            return $this;
        }

        unset($this->items[$index]);

        $this->items = array_values($this->items);
        $this->count = count($this->items);

        return $this;
    }
}