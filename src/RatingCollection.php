<?php

namespace Bluewing\Progress;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

class RatingCollection
{
    private array $items = [];
    private bool $readOnly = false;

    /**
     * RatingCollection constructor.
     */
    public function __construct() {}

    /**
     * Add a rating to the collection.  Use named argument to just pass a score.
     *
     * @param int|null $id
     * @param string|null $dateCompleted
     * @param float $score
     */
    public function add(int|null $id = null, string|null $dateCompleted = null, float $score = 0.0): void
    {
        if (! $this->readOnly) {
            $this->items[] = new Rating($id, $dateCompleted, $score);
        }
    }

    /**
     * Add a rating as an array item.
     * The item shape: ['id' => int|null, 'dateCompleted' => string|null, 'score' => float]
     * Example of an array item: ['id' => 1, 'dateCompleted' => '2021-02-28', 'score' => 1.2]
     *
     * @param array $item
     */
    public function addItem(array $item): void
    {
        if (! $this->readOnly) {
            $rating = new Rating();
            $rating->fromArray($item);
            $this->addRating($rating);
        }
    }

    /**
     * Add a rating to the collection.
     *
     * @param Rating $rating
     */
    public function addRating(Rating $rating): void
    {
        if (! $this->readOnly) $this->items[] = $rating;
    }

    /**
     * Add an array of an array of rating props to the collection.
     * The array item shape: ['id' => int|null, 'dateCompleted' => string|null, 'score' => float]
     * Example of an array item: ['id' => 1, 'dateCompleted' => '2021-02-28', 'score' => 1.2]
     *
     * @param array $items
     */
    public function addRatings(array $items): void
    {
        if (! $this->readOnly) {

            /* @var array $item */
            foreach($items as $item) {
                $rating = new Rating();
                $rating->fromArray($item);
                $this->addRating($rating);
            }
        }
    }

    /**
     * Add an array of scores to the collection.
     *
     * @param array $scores
     */
    public function addScores(array $scores): void
    {
        if (! $this->readOnly) {

            /* @var float $score */
            foreach($scores as $score) {
                $this->add(score: $score);
            }
        }
    }

    /**
     * Return the number of items in the collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Return the first rating in the collection if it exists.
     *
     * @return Rating|null
     */
    #[Pure] public function first(): ?Rating
    {
        if ($this->count() > 0) return $this->items[0];

        return null;
    }

    /**
     * Determine if the specified index is in bounds.
     *
     * @param int $index
     * @return void
     */
    private function indexInBounds(int $index): void
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
     * @noinspection PhpUnused
     */
    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }

    /**
     * Return the item at the specified index.
     *
     * @param int $index
     * @return Rating
     */
    public function item(int $index): Rating
    {
        $this->indexInBounds($index);

        return $this->items[$index];
    }

    /**
     * Return the Rating items in the collection as an array.
     *
     * @param bool $asItemArray
     * @param bool $justScores
     * @return array
     */
    #[Pure] public function items(bool $asItemArray = false, bool $justScores = false): array
    {
        if ($asItemArray) {
            if ($this->count() === 0) {
                return [];
            } else {
                $items = [];
                /** @var Rating $item */
                foreach ($this->items as $item) {
                    $items[] = $item->data()->toArray($justScores);
                }

                return $items;
            }
        }

        return $this->items;
    }

    /**
     * Return the last item in the collection if it exists.
     *
     * @return Rating|null
     */
    #[Pure] public function last(): ?Rating
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
     * @noinspection PhpUnused
     */
    public function makeReadOnly(): void
    {
        if (! $this->readOnly) $this->readOnly = true;
    }

    /**
     * Remove all items from the collection and return the collection.
     *
     * @return $this
     */
    public function remove(): RatingCollection
    {
        if ($this->readOnly) return $this;

        $this->items = [];

        return $this;
    }

    /**
     * Remove an item from the collection, at the specified index, reindex the items, and return the collection.
     *
     * @param int $index
     * @return $this
     */
    public function removeAt(int $index): RatingCollection
    {
        if ($this->readOnly) return $this;

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
    #[Pure] public function scores(): array
    {
        $scores = [];

        /* @var Rating $item */
        foreach($this->items as $item) {
            $scores[] = $item->data()->score;
        }

        return $scores;
    }
}