<?php

namespace Bluewing\Progress\Tests;

use Bluewing\Algorithms2015\AlgorithmManager;
use Bluewing\Progress\RatingCollection;
use Bluewing\Progress\Structs\ClinicalCutoffStruct;
use Bluewing\Progress\Structs\SawtoothPatternStruct;
use Bluewing\Progress\Structs\ValidityIndicatorsStruct;
use Bluewing\Progress\ValidityIndicators;
use PHPUnit\Framework\TestCase;

class ValidityIndicatorsTest extends TestCase
{
    const ADOLESCENT = 1;
    const ADULT = 2;
    const CHILD = 3;

    /** @test */
    public function it_returns_an_instance_of_the_specified_class()
    {
        $age = self::ADOLESCENT;

        $ratings = new RatingCollection;

        $ratings->add(1, '', 12.1);
        $ratings->add(2, '', 3.2);
        $ratings->add(3, '', 9.7);
        $ratings->add(4, '', 6.7);
        $ratings->add(5, '', 8.9);
        $ratings->add(6, '', 9.1);
        $ratings->add(7, '', 10.2);

        $manager = new AlgorithmManager;

        $algorithm = $manager->getFor($age,$ratings->count());
        //$algorithmShortTerm = $manager->getFor($age, 0);

        $validityIndicators = new ValidityIndicators($algorithm, $ratings);

        $this->assertInstanceOf(ValidityIndicators::class, $validityIndicators);
    }

    /** @test */
    public function calling_the_data_method_returns_an_instance_of_the_specified_class()
    {
        $age = self::ADOLESCENT;

        $ratings = new RatingCollection;

        $ratings->add(1, '', 12.1);
        $ratings->add(2, '', 3.2);
        $ratings->add(3, '', 9.7);
        $ratings->add(4, '', 6.7);
        $ratings->add(5, '', 8.9);
        $ratings->add(6, '', 9.1);
        $ratings->add(7, '', 10.2);

        $manager = new AlgorithmManager;

        $algorithm = $manager->getFor($age,$ratings->count());
        //$algorithmShortTerm = $manager->getFor($age, 0);

        $validityIndicators = new ValidityIndicators($algorithm, $ratings);

        $this->assertInstanceOf(ValidityIndicatorsStruct::class, $validityIndicators->data());
    }

    /** @test */
    public function calling_the_data_method_with_ratings_returns_accurate_data()
    {
        $age = self::ADOLESCENT;

        $ratings = new RatingCollection;

        $ratings->add(1, '', 12.1);
        $ratings->add(2, '', 3.2);
        $ratings->add(3, '', 9.7);
        $ratings->add(4, '', 6.7);
        $ratings->add(5, '', 8.9);
        $ratings->add(6, '', 9.1);
        $ratings->add(7, '', 10.2);

        $manager = new AlgorithmManager;

        $algorithm = $manager->getFor($age,$ratings->count());
        //$algorithmShortTerm = $manager->getFor($age, 0);

        $validityIndicators = new ValidityIndicators($algorithm, $ratings);

        $data = $validityIndicators->data();

        $this->assertInstanceOf(ValidityIndicatorsStruct::class, $data);
        $this->assertInstanceOf(ClinicalCutoffStruct::class, $data->clinicalCutoff);
        $this->assertInstanceOf(SawtoothPatternStruct::class, $data->sawtoothPattern);
        $this->assertEquals(false, $data->firstRatingAbove32);
        $this->assertEquals(false, $data->zeroOrOneMeetings);
    }

    /** @test */
    public function calling_the_data_method_with_no_ratings_returns_accurate_data()
    {
        $age = self::ADOLESCENT;

        $ratings = new RatingCollection;

        $manager = new AlgorithmManager;

        $algorithm = $manager->getFor($age,$ratings->count());
        //$algorithmShortTerm = $manager->getFor($age, 0);

        $validityIndicators = new ValidityIndicators($algorithm, $ratings);

        $data = $validityIndicators->data();

        $this->assertInstanceOf(ValidityIndicatorsStruct::class, $data);
        $this->assertInstanceOf(ClinicalCutoffStruct::class, $data->clinicalCutoff);
        $this->assertInstanceOf(SawtoothPatternStruct::class, $data->sawtoothPattern);
        $this->assertEquals(false, $data->firstRatingAbove32);
        $this->assertEquals(true, $data->zeroOrOneMeetings);
    }

    /** @test */
    public function calling_the_data_method_with_first_rating_over_32_returns_accurate_data()
    {
        $age = self::ADOLESCENT;

        $ratings = new RatingCollection;

        $ratings->add(1, '', 38.0);
        $ratings->add(2, '', 3.2);
        $ratings->add(3, '', 9.7);

        $manager = new AlgorithmManager;

        $algorithm = $manager->getFor($age,$ratings->count());
        //$algorithmShortTerm = $manager->getFor($age, 0);

        $validityIndicators = new ValidityIndicators($algorithm, $ratings);

        $data = $validityIndicators->data();

        $this->assertInstanceOf(ValidityIndicatorsStruct::class, $data);
        $this->assertInstanceOf(ClinicalCutoffStruct::class, $data->clinicalCutoff);
        $this->assertInstanceOf(SawtoothPatternStruct::class, $data->sawtoothPattern);
        $this->assertEquals(true, $data->firstRatingAbove32);
        $this->assertEquals(false, $data->zeroOrOneMeetings);
    }
}