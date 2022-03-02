<?php

namespace Bluewing\Progress\Tests;

use Bluewing\Progress\Rating;
use Bluewing\Progress\RatingCollection;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RatingCollectionTest extends TestCase
{
    /** @test */
    public function it_appends_a_collection_using_the_add_method_with_valid_scores()
    {
        $ratings = new RatingCollection;

        $ratings->add(null, null, 1.2);
        $ratings->add(id: 1, score: 2.6);
        $ratings->add(score: 40.0);

        $this->assertEquals(3, $ratings->count());

        $rating = new Rating(2, score: 5.2);
        $ratings->addRating($rating);

        $this->assertEquals(4, $ratings->count());

        $this->assertEquals([1.2, 2.6, 40.0, 5.2], $ratings->scores());
    }

    /** @test */
    public function it_throws_an_exception_when_adding_a_rating_with_an_invalid_score()
    {
        $ratings = new RatingCollection;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid score.  It must be between 0.0 and 40.0.');

        $ratings->add(null, null, 60.0);
    }

    /** @test */
    public function it_throws_an_exception_when_adding_a_rating_as_an_array_with_an_invalid_score()
    {
        $ratings = new RatingCollection;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid score.  It must be between 0.0 and 40.0.');

        $ratings->addItem(['id' => 1, 'dateCompleted' => null, 'score' => 40.1]);
    }

    /** @test */
    public function it_creates_a_collection_using_the_array_methods()
    {
        $ratings = new RatingCollection;

        $ratings->addRatings([
            ['id' => 1, 'dateCompleted' => '2022-01-01', 'score' => 10.2],
            ['id' => 2, 'dateCompleted' => '2022-01-08', 'score' => 6.2],
            ['id' => 3, 'dateCompleted' => '2022-01-15', 'score' => 19.3],
        ]);

        $this->assertEquals(3, $ratings->count());

        $ratings->addScores([1.2, 3.4, 6.5, 8.9, 9.6]);

        $this->assertEquals(8, $ratings->count());
    }

    /** @test */
    public function it_throws_an_exception_when_adding_an_array_with_an_invalid_score()
    {
        $ratings = new RatingCollection;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid score.  It must be between 0.0 and 40.0.');

        $ratings->addScores([1.2, 6.9, -1.0, 60.0]);
    }

    /** @test */
    public function it_returns_the_first_item_as_a_RatingStruct_if_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([2.3, 30.2, 0.0, 9.1]);

        $this->assertInstanceOf(Rating::class, $ratings->first());
    }

    /** @test */
    public function it_returns_the_first_item_as_null_if_no_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([]);

        $this->assertNull($ratings->first());
    }

    /** @test */
    public function it_returns_the_first_item_score_if_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([2.3, 30.2, 0.0, 9.1]);

        $this->assertEquals(2.3, $ratings->first()?->data()->score);
    }

    /** @test */
    public function it_returns_the_first_item_score_as_null_if_no_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([]);

        $this->assertNull($ratings->first()?->data()->score);
    }

    /** @test */
    public function it_returns_the_last_item_as_rating_instance_if_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([2.3, 30.2, 0.0, 9.1]);

        $this->assertInstanceOf(Rating::class, $ratings->last());
    }

    /** @test */
    public function it_returns_the_last_item_as_null_if_no_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([]);

        $this->assertNull($ratings->last());
    }

    /** @test */
    public function it_returns_the_last_item_score_if_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([2.3, 30.2, 0.0, 9.1]);

        $this->assertEquals(9.1, $ratings->last()?->data()->score);
    }

    /** @test */
    public function it_returns_the_last_item_score_as_null_if_no_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([]);

        $this->assertNull($ratings->last()?->data()->score);
    }

    /** @test */
    public function it_returns_an_array_of_items_when_there_are_collection_items()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([2.3, 30.2, 0.0, 9.1]);

        $this->assertIsArray($ratings->items());
        $this->assertCount(4, $ratings->items());
        $this->assertInstanceOf(Rating::class, $ratings->items()[0]);
    }

    /** @test */
    public function it_returns_an_array_of_each_item_as_an_array_when_there_are_collection_items()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([2.3, 30.2, 0.0, 9.1]);

        $this->assertIsArray($ratings->items(true));
        $this->assertCount(4, $ratings->items(true));
        $this->assertIsArray($ratings->items(true)[0]);

        $itemAsArray = $ratings->items(true)[0];

        $this->assertEquals(['id' => NULL, 'dateCompleted' => NULL, 'score' => 2.3], $itemAsArray);
    }

    /** @test */
    public function it_returns_an_array_of_scores_when_there_are_collection_items()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([2.3, 30.2, 0.0, 9.1]);

        $this->assertIsArray($ratings->scores());
        $this->assertCount(4, $ratings->scores());
    }

    /** @test */
    public function it_returns_an_empty_array_of_scores_when_there_are_no_collection_items()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([]);

        $this->assertIsArray($ratings->scores());
        $this->assertCount(0, $ratings->scores());
    }

    /** @test */
    public function it_removes_all_items_in_the_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([1.0, 10.0, 40.0]);

        $ratings->remove();
        $this->assertEquals(0, $ratings->count());
    }

    /** @test */
    public function it_removes_an_item_at_the_specified_index_when_remove_at_is_called_with_index_in_bounds()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([1.0, 10.0, 40.0]);

        $ratings = $ratings->removeAt(0);
        $this->assertEquals(2, $ratings->count());
    }

    /** @test */
    public function it_throws_an_exception_when_remove_at_is_called_and_there_are_no_items()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The index is invalid.  There are no items in the collection.');

        $ratings->removeAt(-1);
    }

    /** @test */
    public function it_throws_an_exception_when_remove_at_index_is_out_of_bound_lower_end()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([1.0, 10.0, 40.0]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The index is invalid.  The lower bound of the index is 0.');

        $ratings->removeAt(-1);
    }

    /** @test */
    public function it_throws_an_exception_when_remove_at_index_is_out_of_bounds_upper_end()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([1.0, 10.0, 40.0]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The index is invalid.  The upper bound of the index is count() - 1.');

        $ratings->removeAt(3);
    }

    /** @test */
    public function it_returns_items_in_the_scores_array_as_float_values()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([1.0, 10.0, 40.0]);

        $this->assertIsArray($ratings->scores());

        $scores = $ratings->scores();
        $this->assertIsFloat($scores[0]);
    }
}