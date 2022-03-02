<?php

namespace Bluewing\Progress\Tests;

use Bluewing\Progress\EtrTarget;
use Bluewing\Progress\Rater;
use Bluewing\Progress\RatingCollection;
use PHPUnit\Framework\TestCase;

class EtrTargetTest extends TestCase
{
    const ADOLESCENT = 1;
    const ADULT = 2;
    const CHILD = 3;

    /** @test */
    public function it_returns_the_expected_change()
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

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

        $this->assertEquals(12.87, round($data->expectedChange, 2, PHP_ROUND_HALF_UP));
    }

    /** @test */
    public function it_returns_whether_or_not_the_etr_target_was_met()
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

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

        $this->assertTrue($data->met);
    }

    /** @test */
    public function it_returns_the_etr_target_met_percent()
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

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

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

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

        $this->assertEquals(69.17, round($data->metPercent, 2, PHP_ROUND_HALF_UP));
    }

    /** @test */
    public function it_returns_whether_the_etr_target_was_met_at_the_two_predefined_percent_levels()
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

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

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

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

        $met = $data->metPercent67;

        $this->assertTrue($met);
    }

    /** @test */
    public function return_the_etr_target_value_for_a_meeting()
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

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

        $this->assertEquals(24.97, round($data->value, 2, PHP_ROUND_HALF_UP));
    }

    /** @test */
    public function return_initialized_values_when_using_st_adolescent_algorithm_and_no_ratings()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

        $this->assertEquals(0.0, $data->expectedChange);
        $this->assertEquals(0.0, $data->value);
        $this->assertEquals(0.0, $data->metPercent);
        $this->assertFalse($data->met);
        $this->assertFalse($data->metPercent50);
        $this->assertFalse($data->metPercent67);
    }

    /** @test */
    public function etr_target_value_is_correct()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 19.2);
        $ratings->add(2, '', 20.3);

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

        $this->assertEquals(9.12, round($data->expectedChange, 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(28.32, round($data->value, 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(12.06, round($data->metPercent, 2, PHP_ROUND_HALF_UP));
        $this->assertFalse($data->met);
        $this->assertFalse($data->metPercent50);
        $this->assertFalse($data->metPercent67);
    }

    /** @test */
    public function etr_target_not_met()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 20.1);
        $ratings->add(2, '', 22.2);

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

        $this->assertEquals(8.65, round($data->expectedChange, 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(28.75, round($data->value, 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(24.28, round($data->metPercent, 2, PHP_ROUND_HALF_UP));
        $this->assertFalse($data->met);
        $this->assertFalse($data->metPercent50);
        $this->assertFalse($data->metPercent67);
    }

    /** @test */
    public function etr_target_met()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 20.1);
        $ratings->add(2, '', 36.2);

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

        $this->assertEquals(8.65, round($data->expectedChange, 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(28.75, round($data->value, 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(100.00, round($data->metPercent, 2, PHP_ROUND_HALF_UP));
        $this->assertTrue($data->met);
        $this->assertTrue($data->metPercent50);
        $this->assertTrue($data->metPercent67);
    }

    /** @test */
    public function etr_target_met_percent_is_correct()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 19.2);
        $ratings->add(2, '', 24.3);

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

        $this->assertEquals(9.12, round($data->expectedChange, 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(28.32, round($data->value, 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(55.90, round($data->metPercent, 2, PHP_ROUND_HALF_UP));
        $this->assertFalse($data->met);
        $this->assertTrue($data->metPercent50);
        $this->assertFalse($data->metPercent67);
    }

    /** @test */
    public function etr_target_predicted_change_percent_met_is_false()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 19.2);
        $ratings->add(2, '', 23.2);

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

        $this->assertEquals(9.12, round($data->expectedChange, 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(28.32, round($data->value, 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(43.84, round($data->metPercent, 2, PHP_ROUND_HALF_UP));
        $this->assertFalse($data->met);
        $this->assertFalse($data->metPercent50);
        $this->assertFalse($data->metPercent67);
    }

    /** @test */
    public function etr_target_predicted_change_percent_met_is_true()
    {
        $rater = new Rater($this::ADOLESCENT);
        $ratings = new RatingCollection;

        $ratings->add(1, '', 19.2);
        $ratings->add(2, '', 23.2);

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

        $this->assertEquals(9.12, round($data->expectedChange, 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(28.32, round($data->value, 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(43.84, round($data->metPercent, 2, PHP_ROUND_HALF_UP));
        $this->assertFalse($data->met);
        $this->assertFalse($data->metPercent50);
        $this->assertFalse($data->metPercent67);

        $ratings = new RatingCollection;

        $ratings->add(1, '', 19.2);
        $ratings->add(2, '', 29.3);

        $etrTarget = new EtrTarget($rater, $ratings);

        $data = $etrTarget->data();

        $this->assertEquals(9.12, round($data->expectedChange, 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(28.32, round($data->value, 2, PHP_ROUND_HALF_UP));
        $this->assertEquals(100.00, round($data->metPercent, 2, PHP_ROUND_HALF_UP));
        $this->assertTrue($data->met);
        $this->assertTrue($data->metPercent50);
        $this->assertTrue($data->metPercent67);
    }
}