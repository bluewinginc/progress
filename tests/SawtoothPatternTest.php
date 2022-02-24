<?php

namespace Bluewing\Progress\Tests;

use Bluewing\Progress\RatingCollection;
use Bluewing\Progress\SawtoothPattern;
use PHPUnit\Framework\TestCase;

class SawtoothPatternTest extends TestCase
{
    /** @test */
    public function return_initialized_sawtooth_pattern_values_with_no_ratings()
    {
        $ratings = new RatingCollection;

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(0, $sawtoothPatternData->directionChanges);
        $this->assertEquals(0, $sawtoothPatternData->teeth);
        $this->assertFalse($sawtoothPatternData->has);
    }

    /** @test */
    public function sawtooth_pattern_is_false_with_one_rating()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 5.0);

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(0, $sawtoothPatternData->directionChanges);
        $this->assertEquals(0, $sawtoothPatternData->teeth);
        $this->assertFalse($sawtoothPatternData->has);
    }

    /** @test */
    public function sawtooth_pattern_is_false_with_no_direction_changes_of_less_than_six_points()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 4.0);
        $ratings->add(0, '2020-01-16', 7.9);
        $ratings->add(0, '2020-01-16', 12.2);
        $ratings->add(0, '2020-01-16', 15.7);
        $ratings->add(0, '2020-01-16', 20.2);

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(0, $sawtoothPatternData->directionChanges);
        $this->assertEquals(0, $sawtoothPatternData->teeth);
        $this->assertFalse($sawtoothPatternData->has);
    }

    /** @test */
    public function sawtooth_pattern_is_false_with_one_direction_change()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 22.0);    //  0
        $ratings->add(0, '2020-01-16', 27.9);    // +5.9
        $ratings->add(0, '2020-01-16', 19.2);    // -6.7 = 1 direction changes
        $ratings->add(0, '2020-01-16', 12.1);    // -7.1
        $ratings->add(0, '2020-01-16', 2.2);     // -9.9

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(1, $sawtoothPatternData->directionChanges);
        $this->assertEquals(0, $sawtoothPatternData->teeth);
        $this->assertFalse($sawtoothPatternData->has);
    }

    /** @test */
    public function sawtooth_pattern_is_false_with_two_direction_changes()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 22.0);    //  0
        $ratings->add(0, '2020-01-16', 27.9);    // +5.9
        $ratings->add(0, '2020-01-16', 19.2);    // -8.7     = 1 down
        $ratings->add(0, '2020-01-16', 20.1);    // +0.9
        $ratings->add(0, '2020-01-16', 35.2);    // +15.1    = 1 up
        $ratings->add(0, '2020-01-16', 30.4);    // -4.8

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(2, $sawtoothPatternData->directionChanges);
        $this->assertEquals(1, $sawtoothPatternData->teeth);
        $this->assertFalse($sawtoothPatternData->has);
    }

    /** @test */
    public function sawtooth_pattern_is_false_with_three_direction_changes()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 22.0);    //  0
        $ratings->add(0, '2020-01-16', 27.9);    // +5.9
        $ratings->add(0, '2020-01-16', 19.2);    // -8.7 = 1
        $ratings->add(0, '2020-01-16', 20.1);    // +0.9
        $ratings->add(0, '2020-01-16', 35.2);    // +15.1 = 1
        $ratings->add(0, '2020-01-16', 28.4);    // -6.8 = 1
        $ratings->add(0, '2020-01-16', 30.2);    // +1.8

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(3, $sawtoothPatternData->directionChanges);
        $this->assertEquals(2, $sawtoothPatternData->teeth);
        $this->assertFalse($sawtoothPatternData->has);
    }

    /** @test */
    public function sawtooth_pattern_is_true_with_four_direction_changes()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 22.0);    //  0
        $ratings->add(0, '2020-01-16', 29.0);    // +7.0 = 1
        $ratings->add(0, '2020-01-16', 19.2);    // -9.8 = 2
        $ratings->add(0, '2020-01-16', 20.1);    // +0.9
        $ratings->add(0, '2020-01-16', 35.2);    // +15.1 = 3
        $ratings->add(0, '2020-01-16', 28.4);    // -6.8 = 4
        $ratings->add(0, '2020-01-16', 30.2);    // +1.8

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(4, $sawtoothPatternData->directionChanges);
        $this->assertEquals(3, $sawtoothPatternData->teeth);
        $this->assertTrue($sawtoothPatternData->has);
    }

    /** @test */
    public function direction_change_count_is_0_with_no_ratings()
    {
        $ratings = new RatingCollection;

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(0, $sawtoothPatternData->directionChanges);
    }

    /** @test */
    public function direction_change_count_is_0_when_passed_one_rating()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 22.0);

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(0, $sawtoothPatternData->directionChanges);
    }

    /** @test */
    public function direction_change_count_is_0_when_passed_two_ratings_with_diff_less_than_six()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 22.0);    //  0
        $ratings->add(0, '2020-01-16', 27.9);    // +5.9

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(0, $sawtoothPatternData->directionChanges);
    }

    /** @test */
    public function direction_change_count_is_1_when_passed_two_ratings_with_diff_of_six()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 22.0);    //  0
        $ratings->add(0, '2020-01-16', 28.0);    // +6 = 1

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(1, $sawtoothPatternData->directionChanges);
        $this->assertEquals(0, $sawtoothPatternData->teeth);
    }

    /** @test */
    public function direction_change_count_is_1_when_passed_two_ratings_with_diff_greater_than_six()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 22.0);    // 0
        $ratings->add(0, '2020-01-16', 28.1);    // +6.1 = 1

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(1, $sawtoothPatternData->directionChanges);
        $this->assertEquals(0, $sawtoothPatternData->teeth);
    }

    /** @test */
    public function direction_change_count_is_0()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 22.0);    //  0
        $ratings->add(0, '2020-01-16', 27.9);    // +5.9
        $ratings->add(0, '2020-01-16', 22.2);    // -5.7
        $ratings->add(0, '2020-01-16', 20.1);    // -2.1
        $ratings->add(0, '2020-01-16', 15.2);    // -4.9

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(0, $sawtoothPatternData->directionChanges);
        $this->assertEquals(0, $sawtoothPatternData->teeth);
    }

    /** @test */
    public function direction_change_count_is_1()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 22.0);    //  0
        $ratings->add(0, '2020-01-16', 27.9);    // +5.9
        $ratings->add(0, '2020-01-16', 19.2);    // -8.7 = 1
        $ratings->add(0, '2020-01-16', 20.1);    // +0.9
        $ratings->add(0, '2020-01-16', 10.2);    // -9.9

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(1, $sawtoothPatternData->directionChanges);
        $this->assertEquals(0, $sawtoothPatternData->teeth);
    }

    /** @test */
    public function direction_change_count_is_2()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 22.0);    //  0
        $ratings->add(0, '2020-01-16', 27.9);    // +5.9
        $ratings->add(0, '2020-01-16', 19.2);    // -8.7 = 1
        $ratings->add(0, '2020-01-16', 20.1);    // +0.9
        $ratings->add(0, '2020-01-16', 35.2);    // +14.9 = 1

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(2, $sawtoothPatternData->directionChanges);
        $this->assertEquals(1, $sawtoothPatternData->teeth);
    }

    /** @test */
    public function direction_change_count_is_3()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 22.0);    // 0
        $ratings->add(0, '2020-01-16', 27.9);    // +5.9
        $ratings->add(0, '2020-01-16', 19.2);    // -8.7 = 1
        $ratings->add(0, '2020-01-16', 20.1);    // +0.9
        $ratings->add(0, '2020-01-16', 35.2);    // +15.1 = 2
        $ratings->add(0, '2020-01-16', 28.4);    // -6.8 = 3

        $sawtoothPattern = new SawtoothPattern($ratings);

        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(3, $sawtoothPatternData->directionChanges);
        $this->assertEquals(2, $sawtoothPatternData->teeth);
    }

    /** @test */
    public function direction_change_count_is_4()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 22.0);    //  0
        $ratings->add(0, '2020-01-16', 27.9);    // +5.9
        $ratings->add(0, '2020-01-16', 19.2);    // -8.7 = 1
        $ratings->add(0, '2020-01-16', 20.1);    // +0.9
        $ratings->add(0, '2020-01-16', 35.2);    // +15.1 = 2
        $ratings->add(0, '2020-01-16', 28.4);    // -6.8 = 3
        $ratings->add(0, '2020-01-16', 35.2);    // +6.8 = 4

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(4, $sawtoothPatternData->directionChanges);
        $this->assertEquals(3, $sawtoothPatternData->teeth);
    }

    /** @test */
    public function direction_change_count_is_0_when_there_are_no_six_point_changes_in_positive_direction()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 2.0);     //  0
        $ratings->add(0, '2020-01-16', 5.0);     // +3.0
        $ratings->add(0, '2020-01-16', 10.0);    // +5.0
        $ratings->add(0, '2020-01-16', 15.0);    // +5.0
        $ratings->add(0, '2020-01-16', 20.0);    // +5.0
        $ratings->add(0, '2020-01-16', 25.0);    // +5.0
        $ratings->add(0, '2020-01-16', 30.0);    // +5.0

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(0, $sawtoothPatternData->directionChanges);
        $this->assertEquals(0, $sawtoothPatternData->teeth);
    }

    /** @test */
    public function direction_change_count_is_0_when_there_are_no_six_point_changes_in_negative_direction()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 38.0);    //  0
        $ratings->add(0, '2020-01-16', 36.0);    // -2.0
        $ratings->add(0, '2020-01-16', 31.0);    // -5.0
        $ratings->add(0, '2020-01-16', 26.0);    // -5.0
        $ratings->add(0, '2020-01-16', 21.0);    // -5.0
        $ratings->add(0, '2020-01-16', 17.0);    // -4.0
        $ratings->add(0, '2020-01-16', 15.0);    // -2.0

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(0, $sawtoothPatternData->directionChanges);
        $this->assertEquals(0, $sawtoothPatternData->teeth);
    }

    /** @test */
    public function direction_change_count_is_0_when_there_are_no_six_point_changes_in_alternating_directions()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 2.0);     //  0
        $ratings->add(0, '2020-01-16', 5.0);     // +3.0
        $ratings->add(0, '2020-01-16', 3.0);     // -2.0
        $ratings->add(0, '2020-01-16', 8.0);     // +5.0
        $ratings->add(0, '2020-01-16', 7.0);     // -1.0
        $ratings->add(0, '2020-01-16', 12.0);    // +5.0
        $ratings->add(0, '2020-01-16', 10.0);    // -2.0

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(0, $sawtoothPatternData->directionChanges);
        $this->assertEquals(0, $sawtoothPatternData->teeth);
    }

    /** @test */
    public function direction_change_count_is_1_when_six_point_changes_are_in_positive_direction()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 2.0);     //  0
        $ratings->add(0, '2020-01-16', 8.0);     // +6.0 = 1
        $ratings->add(0, '2020-01-16', 14.0);    // +6.0
        $ratings->add(0, '2020-01-16', 20.0);    // +6.0
        $ratings->add(0, '2020-01-16', 26.0);    // +6.0
        $ratings->add(0, '2020-01-16', 32.0);    // +6.0
        $ratings->add(0, '2020-01-16', 38.0);    // +6.0

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(1, $sawtoothPatternData->directionChanges);
        $this->assertEquals(0, $sawtoothPatternData->teeth);
    }

    /** @test */
    public function direction_change_count_is_1_when_six_point_changes_are_in_negative_direction()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 38.0);    // 0
        $ratings->add(0, '2020-01-16', 32.0);    // -6.0 = 1
        $ratings->add(0, '2020-01-16', 26.0);    // -6.0
        $ratings->add(0, '2020-01-16', 20.0);    // -6.0
        $ratings->add(0, '2020-01-16', 14.0);    // -6.0
        $ratings->add(0, '2020-01-16', 8.0);     // -6.0
        $ratings->add(0, '2020-01-16', 2.0);     // -6.0

        $sawtoothPattern = new SawtoothPattern($ratings);
        $this->assertInstanceOf('Bluewing\Progress\SawtoothPattern', $sawtoothPattern);

        $sawtoothPatternData = $sawtoothPattern->data();
        $this->assertInstanceOf('Bluewing\Progress\Structs\SawtoothPatternStruct', $sawtoothPatternData);

        $this->assertEquals(1, $sawtoothPatternData->directionChanges);
        $this->assertEquals(0, $sawtoothPatternData->teeth);
    }
}