<?php

namespace Bluewing\Progress\Tests;

use Bluewing\Progress\EtrPath;
use Bluewing\Progress\Rater;
use Bluewing\Progress\Rating;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EtrPathTest extends TestCase
{
    const ADOLESCENT = 1;
    const ADULT = 2;
    const CHILD = 3;

    /** @test */
    public function it_returns_an_etr_using_short_term_algorithm()
    {
        $rater = new Rater($this::ADOLESCENT);
        $firstRating = new Rating(score: 12.1);

        $etrPath = new EtrPath($rater, $firstRating, 3);

        $data = $etrPath->data();

        $this->assertCount(8, $data->values);
        $this->assertEquals(12.1, $data->values[0]);
    }

    /** @test */
    public function it_returns_an_etr_using_long_term_algorithm()
    {
        $rater = new Rater($this::ADOLESCENT);

        $firstRating = new Rating(score: 16.1);

        $etrPath = new EtrPath($rater, $firstRating,10);

        $data = $etrPath->data();

        $this->assertCount(18, $data->values);
        $this->assertEquals(16.1, $data->values[0]);
    }

    /** @test */
    public function it_throws_an_exception_when_meetings_is_0()
    {
        $rater = new Rater($this::ADOLESCENT);

        $firstRating = new Rating(score: 16.1);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('There meetings argument must be greater than 0.');

        new EtrPath($rater, $firstRating, 0);
    }

    /** @test */
    public function it_throws_an_exception_when_meetings_is_less_than_0()
    {
        $rater = new Rater($this::ADOLESCENT);

        $firstRating = new Rating(score: 16.1);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('There meetings argument must be greater than 0.');

        new EtrPath($rater, $firstRating, -1);
    }

    /** @test */
    public function it_returns_8_items_using_st_adolescent_algorithm()
    {
        $rater = new Rater($this::ADOLESCENT);

        $firstRating = new Rating(score: 19.2);

        $etrPath = new EtrPath($rater, $firstRating, 3);

        $data = $etrPath->data();

        $this->assertCount(8, $data->values);
        $this->assertEquals(19.2, $data->values[0]);
    }

    /** @test */
    public function it_returns_18_items_using_lt_adolescent_algorithm()
    {
        $rater = new Rater($this::ADOLESCENT, 1);

        $firstRating = new Rating(score: 19.2);

        $etrPath = new EtrPath($rater, $firstRating, 9);

        $data = $etrPath->data();

        $this->assertCount(18, $data->values);
        $this->assertEquals(19.2, $data->values[0]);
    }

    /** @test */
    public function etr_array_contains_known_values_using_st_adolescent_algorithm()
    {
        $rater = new Rater($this::ADOLESCENT);

        $firstRating = new Rating(score: 19.2);

        $etrPath = new EtrPath($rater, $firstRating, 1);

        $data = $etrPath->data();

        $this->assertCount(8, $data->values);
        $this->assertEquals(22.9, $data->values[1]);
        $this->assertEquals(28.3, $data->values[7]);
    }
}