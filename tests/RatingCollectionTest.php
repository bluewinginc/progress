<?php

namespace Bluewing\Progress\Tests;

use Bluewing\Progress\RatingCollection;
use Bluewing\Progress\Structs\RatingStruct;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RatingCollectionTest extends TestCase
{
    /** @test */
    public function create_collection_using_the_add_method_with_valid_scores()
    {
        $ratings = new RatingCollection;
        $ratings->add(null, null, 1.2);
        $ratings->add(null, null, 2.6);
        $ratings->add(null, null, 40.0);

        $this->assertEquals(3, $ratings->count());
    }

    /** @test */
    public function throw_exception_when_adding_an_item_with_an_invalid_score()
    {
        $ratings = new RatingCollection;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid score.  It must be between 0.0 and 40.0.');

        $ratings->add(null, null, 60.0);
    }

    /** @test */
    public function create_collection_using_an_array_of_valid_score_values()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([1.2, 3.4, 6.5, 8.9, 9.6]);

        $this->assertEquals(5, $ratings->count());
    }

    /** @test */
    public function throw_exception_when_adding_an_array_with_an_invalid_score()
    {
        $ratings = new RatingCollection;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid score.  It must be between 0.0 and 40.0.');

        $ratings->addScores([1.2, 6.9, -1.0, 60.0]);
    }

    /** @test */
    public function create_collection_using_a_rating_struct()
    {
        $ratings = new RatingCollection;
        $rating = new RatingStruct;

        $rating->score = 1.2;
        $ratings->addStruct($rating);

        $rating->score = 10.2;
        $ratings->addStruct($rating);

        $this->assertEquals(2, $ratings->count());
    }

    /** @test */
    public function throw_exception_when_adding_a_rating_struct_with_an_invalid_score()
    {
        $ratings = new RatingCollection;
        $rating = new RatingStruct;

        $rating->score = 99.0;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid score.  It must be between 0.0 and 40.0.');

        $ratings->addStruct($rating);
    }

    /** @test */
    public function return_first_item_as_specified_instance_if_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([2.3, 30.2, 0.0, 9.1]);

        $this->assertInstanceOf(RatingStruct::class, $ratings->first());
    }

    /** @test */
    public function return_first_item_as_null_if_no_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([]);

        $this->assertNull($ratings->first());
    }

    /** @test */
    public function return_first_item_score_if_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([2.3, 30.2, 0.0, 9.1]);

        $this->assertEquals(2.3, $ratings->first()?->score);
    }

    /** @test */
    public function return_first_item_score_as_null_if_no_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([]);

        $this->assertNull($ratings->first()?->score);
    }

    /** @test */
    public function return_last_item_as_specified_instance_if_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([2.3, 30.2, 0.0, 9.1]);

        $this->assertInstanceOf(RatingStruct::class, $ratings->last());
    }

    /** @test */
    public function return_last_item_as_null_if_no_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([]);

        $this->assertNull($ratings->last());
    }

    /** @test */
    public function return_last_item_score_if_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([2.3, 30.2, 0.0, 9.1]);

        $this->assertEquals(9.1, $ratings->last()?->score);
    }

    /** @test */
    public function return_last_item_score_as_null_if_no_items_exist_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([]);

        $this->assertNull($ratings->last()?->score);
    }

    /** @test */
    public function return_scores_as_an_array_when_there_are_collection_items()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([2.3, 30.2, 0.0, 9.1]);

        $this->assertIsArray($ratings->scores());
        $this->assertCount(4, $ratings->scores());
    }

    /** @test */
    public function return_scores_as_an_empty_array_when_there_are_no_collection_items()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([]);

        $this->assertIsArray($ratings->scores());
        $this->assertCount(0, $ratings->scores());
    }

    /** @test */
    public function when_remove_is_called_no_items_will_remain_in_collection()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([1.0, 10.0, 40.0]);

        $ratings->remove();
        $this->assertEquals(0, $ratings->count());
    }

    /** @test */
    public function item_will_be_removed_when_remove_at_is_called_with_index_in_bounds()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([1.0, 10.0, 40.0]);

        $ratings = $ratings->removeAt(0);
        $this->assertEquals(2, $ratings->count());
    }

    /** @test */
    public function exception_will_be_thrown_when_remove_at_is_called_and_there_are_no_items()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The index is invalid.  There are no items in the collection.');

        $ratings->removeAt(-1);
    }

    /** @test */
    public function exception_will_be_thrown_when_remove_at_index_is_out_of_bound_lower_end()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([1.0, 10.0, 40.0]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The index is invalid.  The lower bound of the index is 0.');

        $ratings->removeAt(-1);
    }

    /** @test */
    public function exception_will_be_thrown_when_remove_at_index_is_out_of_bounds_upper_end()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([1.0, 10.0, 40.0]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The index is invalid.  The upper bound of the index is count() - 1.');

        $ratings->removeAt(3);
    }

    /** @test */
    public function scores_array_items_will_be_of_type_float()
    {
        $ratings = new RatingCollection;
        $ratings->addScores([1.0, 10.0, 40.0]);

        $this->assertIsArray($ratings->scores());

        $scores = $ratings->scores();
        $this->assertIsFloat($scores[0]);
    }
}