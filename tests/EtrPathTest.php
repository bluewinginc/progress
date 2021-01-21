<?php

namespace Bluewing\Progress\Tests;

use Bluewing\Progress\EtrPath;
use Bluewing\Progress\RatingCollection;
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
        $ratings = new RatingCollection;

        $ratings->add(1, '', 12.1);
        $ratings->add(2, '', 3.2);
        $ratings->add(3, '', 9.7);
        $ratings->add(4, '', 6.7);
        $ratings->add(5, '', 8.9);
        $ratings->add(6, '', 9.1);
        $ratings->add(7, '', 10.2);

        $etrPath = new EtrPath($this::ADOLESCENT, $ratings);

        $data = $etrPath->data();

        $this->assertCount(8, $data->values);
        $this->assertEquals(12.1, $data->values[0]);
    }

    /** @test */
    public function it_returns_an_etr_using_long_term_algorithm()
    {
        $ratings = new RatingCollection;

        $ratings->add(1, '', 12.1);
        $ratings->add(2, '', 3.2);
        $ratings->add(3, '', 9.7);
        $ratings->add(4, '', 6.7);
        $ratings->add(5, '', 8.9);
        $ratings->add(6, '', 9.1);
        $ratings->add(7, '', 10.2);
        $ratings->add(8, '', 20.1);
        $ratings->add(9, '', 32.0);

        $etrPath = new EtrPath($this::ADOLESCENT, $ratings);

        $data = $etrPath->data();

        $this->assertCount(18, $data->values);
        $this->assertEquals(12.1, $data->values[0]);
    }

    /** @test */
    public function throws_invalid_argument_exception_using_st_adolescent_algorithm_and_no_ratings()
    {
        $ratings = new RatingCollection;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('There are no ratings.');

        $etrPath = new EtrPath($this::ADOLESCENT, $ratings);
    }

    /** @test */
    public function returns_8_items_using_st_adolescent_algorithm_and_1_rating()
    {
        $ratings = new RatingCollection;

        $ratings->add(1, '', 19.2);

        $etrPath = new EtrPath($this::ADOLESCENT, $ratings);

        $data = $etrPath->data();

        $this->assertCount(8, $data->values);
        $this->assertEquals(19.2, $data->values[0]);
    }

    /** @test */
    public function returns_18_items_using_lt_adolescent_algorithm_and_1_rating()
    {
        $ratings = new RatingCollection;

        $ratings->add(1, '', 19.2);
        $ratings->add(2, '', 3.2);
        $ratings->add(3, '', 9.7);
        $ratings->add(4, '', 6.7);
        $ratings->add(5, '', 8.9);
        $ratings->add(6, '', 9.1);
        $ratings->add(7, '', 10.2);
        $ratings->add(8, '', 20.1);
        $ratings->add(9, '', 32.0);

        $etrPath = new EtrPath($this::ADOLESCENT, $ratings);

        $data = $etrPath->data();

        $this->assertCount(18, $data->values);
        $this->assertEquals(19.2, $data->values[0]);
    }

    /** @test */
    public function etr_array_contains_known_values_using_st_adolescent_algorithm()
    {
        $ratings = new RatingCollection;

        $ratings->add(1, '', 19.2);

        $etrPath = new EtrPath($this::ADOLESCENT, $ratings);

        $data = $etrPath->data();

        $this->assertCount(8, $data->values);
        $this->assertEquals(22.9, $data->values[1]);
        $this->assertEquals(28.3, $data->values[7]);
    }
}