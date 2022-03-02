<?php

namespace Bluewing\Progress\Tests;

use Bluewing\Progress\Rater;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RaterTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_rater_age_group_is_0()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid rater age group.  It must be set to 1, 2, or 3.');

        new Rater(0);
    }

    /** @test */
    public function it_throws_an_exception_when_rater_age_group_is_below_0()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid rater age group.  It must be set to 1, 2, or 3.');

        new Rater(-1);
    }

    /** @test */
    public function it_throws_an_exception_when_rater_age_group_is_above_3()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid rater age group.  It must be set to 1, 2, or 3.');

        new Rater(4);
    }

    /** @test */
    public function it_returns_a_rater_instance_when_the_age_group_is_valid()
    {
        $rater = new Rater(1);
        $this->assertInstanceOf(Rater::class, $rater);

        $rater = new Rater(2);
        $this->assertInstanceOf(Rater::class, $rater);

        $rater = new Rater(3);
        $this->assertInstanceOf(Rater::class, $rater);
    }

    /** @test */
    public function it_throws_an_exception_when_exclude_from_stats_is_below_0()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid excludeFromStats value.  It must be set to 0 or 1.');

        new Rater(1, -1);
    }

    /** @test */
    public function it_throws_an_exception_when_exclude_from_stats_is_above_1()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid excludeFromStats value.  It must be set to 0 or 1.');

        new Rater(1, 2);
    }

    /** @test */
    public function it_returns_a_rater_instance_when_exclude_from_stats_is_valid()
    {
        $rater = new Rater(1, 0);
        $this->assertInstanceOf(Rater::class, $rater);

        $rater = new Rater(1, 1);
        $this->assertInstanceOf(Rater::class, $rater);
    }
}