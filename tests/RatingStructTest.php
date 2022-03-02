<?php

namespace Bluewing\Progress\Tests;

use Bluewing\Progress\Structs\RatingStruct;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RatingStructTest extends TestCase
{
    /** @test */
    public function it_returns_a_RatingStruct()
    {
        $this->assertInstanceOf(RatingStruct::class, new RatingStruct);
    }

    /** @test */
    public function it_returns_user_entered_data()
    {
        $rs = new RatingStruct;
        $rs->id = 1;
        $rs->dateCompleted = "2020-01-01";
        $rs->score = 14.1;

        $this->assertEquals(1, $rs->id);
        $this->assertEquals('2020-01-01', $rs->dateCompleted);
        $this->assertEquals(14.1, $rs->score);
    }

    /** @test */
    public function it_returns_an_array_of_data()
    {
        $rs = new RatingStruct;
        $rs->id = 1;
        $rs->dateCompleted = "2020-01-01";
        $rs->score = 14.1;

        $data = $rs->toArray();
        $this->assertIsArray($data);
    }
}