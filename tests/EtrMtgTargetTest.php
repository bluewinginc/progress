<?php

namespace Bluewing\Progress\Tests;

use Bluewing\Progress\EtrMtgTarget;
use Bluewing\Progress\Rater;
use Bluewing\Progress\RatingCollection;
use PHPUnit\Framework\TestCase;

class EtrMtgTargetTest extends TestCase
{
    const ADOLESCENT = 1;
    const ADULT = 2;
    const CHILD = 3;

    /** @test */
    public function return_the_expected_change()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 12.1);
        $ratings->add(2, '', 3.2);
        $ratings->add(3, '', 9.7);
        $ratings->add(4, '', 6.7);
        $ratings->add(5, '', 8.9);
        $ratings->add(6, '', 9.1);
        $ratings->add(7, '', 10.2);

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);

        $data = $etrMtgTarget->data();

        $this->assertEquals(12.87, round($data->expectedChange, 2, PHP_ROUND_HALF_UP));
    }

    /** @test */
    public function return_whether_or_not_the_etr_mtg_target_was_met()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 12.1);
        $ratings->add(2, '', 3.2);
        $ratings->add(3, '', 9.7);
        $ratings->add(4, '', 6.7);
        $ratings->add(5, '', 8.9);
        $ratings->add(6, '', 9.1);
        $ratings->add(7, '', 10.2);
        $ratings->add(8, '', 20.1);
        $ratings->add(9, '', 26.0);

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);

        $data = $etrMtgTarget->data();

        $this->assertTrue($data->met);
    }

    /** @test */
    public function return_the_etr_mtg_target_met_percent()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 12.1);
        $ratings->add(2, '', 3.2);
        $ratings->add(3, '', 9.7);
        $ratings->add(4, '', 6.7);
        $ratings->add(5, '', 8.9);
        $ratings->add(6, '', 9.1);
        $ratings->add(7, '', 10.2);
        $ratings->add(8, '', 20.1);
        $ratings->add(9, '', 26.0);

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);

        $data = $etrMtgTarget->data();

        $this->assertEquals(100.0, $data->metPercent);

        $ratings = new RatingCollection;

        $ratings->add(1, '', 12.1);
        $ratings->add(2, '', 3.2);
        $ratings->add(3, '', 9.7);
        $ratings->add(4, '', 6.7);
        $ratings->add(5, '', 8.9);
        $ratings->add(6, '', 9.1);
        $ratings->add(7, '', 10.2);
        $ratings->add(8, '', 20.1);
        $ratings->add(9, '', 21.0);

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);

        $data = $etrMtgTarget->data();

        $this->assertEquals(68.97, round($data->metPercent, 2, PHP_ROUND_HALF_UP));
    }

    /** @test */
    public function return_whether_the_etr_mtg_target_was_met_at_the_50_percent_level()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 12.1);
        $ratings->add(2, '', 3.2);
        $ratings->add(3, '', 9.7);
        $ratings->add(4, '', 6.7);
        $ratings->add(5, '', 8.9);
        $ratings->add(6, '', 9.1);
        $ratings->add(7, '', 10.2);
        $ratings->add(8, '', 20.1);
        $ratings->add(9, '', 26.0);

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);

        $data = $etrMtgTarget->data();

        $met = $data->metPercent50;

        $this->assertTrue($met);

        $ratings = new RatingCollection;

        $ratings->add(1, '', 12.1);
        $ratings->add(2, '', 3.2);
        $ratings->add(3, '', 9.7);
        $ratings->add(4, '', 6.7);
        $ratings->add(5, '', 8.9);
        $ratings->add(6, '', 9.1);
        $ratings->add(7, '', 10.2);
        $ratings->add(8, '', 20.1);
        $ratings->add(9, '', 21.0);

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);

        $data = $etrMtgTarget->data();

        $met = $data->metPercent67;

        $this->assertTrue($met);
    }

    /** @test */
    public function return_the_etr_mtg_target_value_for_a_meeting()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 12.1);
        $ratings->add(2, '', 3.2);
        $ratings->add(3, '', 9.7);
        $ratings->add(4, '', 6.7);
        $ratings->add(5, '', 8.9);
        $ratings->add(6, '', 9.1);
        $ratings->add(7, '', 10.2);
        $ratings->add(8, '', 20.1);
        $ratings->add(9, '', 26.0);

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);

        $value = $etrMtgTarget->value(12.1, 5);

        $this->assertEquals(22.15, round($value, 2, PHP_ROUND_HALF_UP));
    }

    /** @test */
    public function return_data_using_st_adolescent_algorithm_and_no_ratings()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\EtrMtgTarget', $etrMtgTarget);

        $etrMtgTargetData = $etrMtgTarget->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\EtrStruct', $etrMtgTargetData);

        $this->assertEquals(0.0, $etrMtgTargetData->expectedChange);
        $this->assertFalse($etrMtgTargetData->met);
        $this->assertEquals(0.0, $etrMtgTargetData->metPercent);
        $this->assertFalse($etrMtgTargetData->metPercent50);
        $this->assertFalse($etrMtgTargetData->metPercent67);
        $this->assertEquals(0.0, $etrMtgTargetData->value);
    }

    /** @test */
    public function etr_meeting_target_data_for_meeting_2_with_first_19dot2()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 19.2);
        $ratings->add(2, '', 20.3);

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\EtrMtgTarget', $etrMtgTarget);

        $etrMtgTargetData = $etrMtgTarget->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\EtrStruct', $etrMtgTargetData);

        $this->assertEquals(3.74, round($etrMtgTargetData->expectedChange, 2, PHP_ROUND_HALF_UP));
        $this->assertFalse($etrMtgTargetData->met);
        $this->assertEquals(29.44, round($etrMtgTargetData->metPercent, 2, PHP_ROUND_HALF_UP));
        $this->assertFalse($etrMtgTargetData->metPercent50);
        $this->assertFalse($etrMtgTargetData->metPercent67);
        $this->assertEquals(22.94, round($etrMtgTargetData->value, 2, PHP_ROUND_HALF_UP));
    }

    /** @test */
    public function return_false_with_scores_when_etr_meeting_target_is_not_met()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 20.1);
        $ratings->add(2, '', 22.2);

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\EtrMtgTarget', $etrMtgTarget);

        $etrMtgTargetData = $etrMtgTarget->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\EtrStruct', $etrMtgTargetData);

        $this->assertFalse($etrMtgTargetData->met);
    }

    /** @test */
    public function return_true_with_scores_when_etr_meeting_target_is_met()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 20.1);
        $ratings->add(2, '', 36.2);

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\EtrMtgTarget', $etrMtgTarget);

        $etrMtgTargetData = $etrMtgTarget->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\EtrStruct', $etrMtgTargetData);

        $this->assertTrue($etrMtgTargetData->met);
    }

    /** @test */
    public function return_empty_values_when_first_rating_is_above_32()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 34.0);
        $ratings->add(2, '', 32.7);

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\EtrMtgTarget', $etrMtgTarget);

        $etrMtgTargetData = $etrMtgTarget->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\EtrStruct', $etrMtgTargetData);

        $this->assertEquals(0.0, $etrMtgTargetData->expectedChange);
        $this->assertFalse($etrMtgTargetData->met);
        $this->assertEquals(0.0, $etrMtgTargetData->metPercent);
        $this->assertFalse($etrMtgTargetData->metPercent50);
        $this->assertFalse($etrMtgTargetData->metPercent67);
        $this->assertEquals(0.0, $etrMtgTargetData->value);
    }

    /** @test */
    public function return_empty_values_when_using_st_adolescent_algorithm_with_no_ratings()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\EtrMtgTarget', $etrMtgTarget);

        $etrMtgTargetData = $etrMtgTarget->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\EtrStruct', $etrMtgTargetData);

        $this->assertEquals(0.0, $etrMtgTargetData->expectedChange);
        $this->assertFalse($etrMtgTargetData->met);
        $this->assertEquals(0.0, $etrMtgTargetData->metPercent);
        $this->assertFalse($etrMtgTargetData->metPercent50);
        $this->assertFalse($etrMtgTargetData->metPercent67);
        $this->assertEquals(0.0, $etrMtgTargetData->value);
    }

    /** @test */
    public function etr_meeting_target_value_for_meeting_2_is_correct_2()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 19.2);
        $ratings->add(2, '', 20.3);

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\EtrMtgTarget', $etrMtgTarget);

        $etrMtgTargetData = $etrMtgTarget->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\EtrStruct', $etrMtgTargetData);

        $this->assertEquals(22.94, round($etrMtgTargetData->value, 2, PHP_ROUND_HALF_UP));
    }

    /** @test */
    public function return_false_using_scores_where_etr_meeting_target_is_not_met()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 20.1);
        $ratings->add(2, '', 22.2);

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\EtrMtgTarget', $etrMtgTarget);

        $etrMtgTargetData = $etrMtgTarget->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\EtrStruct', $etrMtgTargetData);

        $this->assertFalse($etrMtgTargetData->met);
    }

    /** @test */
    public function return_true_using_scores_where_etr_meeting_target_is_met()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 20.1);
        $ratings->add(2, '', 36.2);

        $etrMtgTarget = new EtrMtgTarget($rater, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\EtrMtgTarget', $etrMtgTarget);

        $etrMtgTargetData = $etrMtgTarget->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\EtrStruct', $etrMtgTargetData);

        $this->assertTrue($etrMtgTargetData->met);
    }
}