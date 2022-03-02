<?php

namespace Bluewing\Progress\Tests;

use Bluewing\Algorithms2015\AlgorithmManager;
use Bluewing\Progress\ClinicalCutoff;
use Bluewing\Progress\RatingCollection;
use PHPUnit\Framework\TestCase;

class ClinicalCutoffTest extends TestCase
{
    const ADOLESCENT = 1;
    const ADULT = 2;
    const CHILD = 3;

    /** @test */
    public function return_data_when_using_short_term_adolescent_algorithm_and_no_rating_scores()
    {
        $ratings = new RatingCollection;

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor($this::ADOLESCENT, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent', $algorithm);

        $clinicalCutoff = new ClinicalCutoff($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\ClinicalCutoff', $clinicalCutoff);

        $data = $clinicalCutoff->data();

        $this->assertEquals(28.0, $data->value);
        $this->assertEquals(0.0, $data->firstRatingScore);
        $this->assertFalse($data->isAbove);
    }

    /** @test */
    public function is_false_using_short_term_adolescent_algorithm_and_first_rating_is_less_than_clinical_cutoff_value()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 14.0);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor($this::ADOLESCENT, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent', $algorithm);

        $clinicalCutoff = new ClinicalCutoff($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\ClinicalCutoff', $clinicalCutoff);

        $data = $clinicalCutoff->data();

        $this->assertEquals(28.0, $data->value);
        $this->assertEquals(14.0, $data->firstRatingScore);
        $this->assertFalse($data->isAbove);
    }

    /** @test */
    public function is_false_using_adolescent_algorithms_when_first_rating_equals_clinical_cutoff_value()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 28.0);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor($this::ADOLESCENT, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent', $algorithm);

        $clinicalCutoff = new ClinicalCutoff($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\ClinicalCutoff', $clinicalCutoff);

        $data = $clinicalCutoff->data();

        $this->assertEquals(28.0, $data->value);
        $this->assertEquals(28.0, $data->firstRatingScore);
        $this->assertFalse($data->isAbove);

        $ratings->add(0, '2020-01-16', 19.0);
        $ratings->add(0, '2020-01-16', 20.0);
        $ratings->add(0, '2020-01-16', 21.0);
        $ratings->add(0, '2020-01-16', 22.0);
        $ratings->add(0, '2020-01-16', 23.0);
        $ratings->add(0, '2020-01-16', 35.0);
        $ratings->add(0, '2020-01-16', 36.0);
        $ratings->add(0, '2020-01-16', 37.0);
        $ratings->add(0, '2020-01-16', 38.0);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor($this::ADOLESCENT, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\LongTerm\LongTermAdolescent', $algorithm);

        $clinicalCutoff = new ClinicalCutoff($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\ClinicalCutoff', $clinicalCutoff);

        $data = $clinicalCutoff->data();

        $this->assertEquals(28.0, $data->value);
        $this->assertEquals(28.0, $data->firstRatingScore);
        $this->assertFalse($data->isAbove);
    }

    /** @test */
    public function is_true_using_adolescent_algorithms_when_first_rating_is_greater_than_clinical_cutoff_value()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 28.1);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor($this::ADOLESCENT, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent', $algorithm);

        $clinicalCutoff = new ClinicalCutoff($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\ClinicalCutoff', $clinicalCutoff);

        $data = $clinicalCutoff->data();

        $this->assertEquals(28.0, $data->value);
        $this->assertEquals(28.1, $data->firstRatingScore);
        $this->assertTrue($data->isAbove);

        $ratings->add(0, '2020-01-16', 19.0);
        $ratings->add(0, '2020-01-16', 20.0);
        $ratings->add(0, '2020-01-16', 21.0);
        $ratings->add(0, '2020-01-16', 22.0);
        $ratings->add(0, '2020-01-16', 23.0);
        $ratings->add(0, '2020-01-16', 35.0);
        $ratings->add(0, '2020-01-16', 36.0);
        $ratings->add(0, '2020-01-16', 37.0);
        $ratings->add(0, '2020-01-16', 38.0);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor($this::ADOLESCENT, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\LongTerm\LongTermAdolescent', $algorithm);

        $clinicalCutoff = new ClinicalCutoff($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\ClinicalCutoff', $clinicalCutoff);

        $data = $clinicalCutoff->data();

        $this->assertEquals(28.0, $data->value);
        $this->assertEquals(28.1, $data->firstRatingScore);
        $this->assertTrue($data->isAbove);
    }

    /** @test */
    public function is_false_using_adult_algorithms_when_first_rating_equals_clinical_cutoff_value()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 25.0);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor($this::ADULT, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdult', $algorithm);

        $clinicalCutoff = new ClinicalCutoff($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\ClinicalCutoff', $clinicalCutoff);

        $data = $clinicalCutoff->data();

        $this->assertEquals(25.0, $data->value);
        $this->assertEquals(25.0, $data->firstRatingScore);
        $this->assertFalse($data->isAbove);

        $ratings->add(0, '2020-01-16', 19.0);
        $ratings->add(0, '2020-01-16', 20.0);
        $ratings->add(0, '2020-01-16', 21.0);
        $ratings->add(0, '2020-01-16', 22.0);
        $ratings->add(0, '2020-01-16', 23.0);
        $ratings->add(0, '2020-01-16', 35.0);
        $ratings->add(0, '2020-01-16', 36.0);
        $ratings->add(0, '2020-01-16', 37.0);
        $ratings->add(0, '2020-01-16', 38.0);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor($this::ADULT, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\LongTerm\LongTermAdult', $algorithm);

        $clinicalCutoff = new ClinicalCutoff($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\ClinicalCutoff', $clinicalCutoff);

        $data = $clinicalCutoff->data();

        $this->assertEquals(25.0, $data->value);
        $this->assertEquals(25.0, $data->firstRatingScore);
        $this->assertFalse($data->isAbove);
    }

    /** @test */
    public function is_true_using_adult_algorithms_when_first_rating_is_greater_than_clinical_cutoff_value()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 25.1);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor($this::ADULT, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdult', $algorithm);

        $clinicalCutoff = new ClinicalCutoff($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\ClinicalCutoff', $clinicalCutoff);

        $data = $clinicalCutoff->data();

        $this->assertEquals(25.0, $data->value);
        $this->assertEquals(25.1, $data->firstRatingScore);
        $this->assertTrue($data->isAbove);

        $ratings->add(0, '2020-01-16', 19.0);
        $ratings->add(0, '2020-01-16', 20.0);
        $ratings->add(0, '2020-01-16', 21.0);
        $ratings->add(0, '2020-01-16', 22.0);
        $ratings->add(0, '2020-01-16', 23.0);
        $ratings->add(0, '2020-01-16', 35.0);
        $ratings->add(0, '2020-01-16', 36.0);
        $ratings->add(0, '2020-01-16', 37.0);
        $ratings->add(0, '2020-01-16', 38.0);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor($this::ADULT, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\LongTerm\LongTermAdult', $algorithm);

        $clinicalCutoff = new ClinicalCutoff($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\ClinicalCutoff', $clinicalCutoff);

        $data = $clinicalCutoff->data();

        $this->assertEquals(25.0, $data->value);
        $this->assertEquals(25.1, $data->firstRatingScore);
        $this->assertTrue($data->isAbove);
    }
}