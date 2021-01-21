<?php

namespace Bluewing\Progress\Tests;

use Bluewing\Algorithms2015\AlgorithmManager;
use Bluewing\Progress\Milestones;
use Bluewing\Progress\RatingCollection;
use PHPUnit\Framework\TestCase;

class MilestonesTest extends TestCase
{
    /** @test */
    public function return_false_for_all_values_when_using_st_adolescent_algorithm_and_no_ratings()
    {
        $ratings = new RatingCollection;

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor(1, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent', $algorithm);

        $milestones = new Milestones($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\Milestones', $milestones);

        $data = $milestones->data();

        $this->assertFalse($data->cscMet);
        $this->assertFalse($data->rcMet);
        $this->assertFalse($data->rcOrCscMet);
    }

    /** @test */
    public function return_false_for_all_values_when_using_st_adolescent_algorithm_and_1_rating()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 20.1);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor(1, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent', $algorithm);

        $milestones = new Milestones($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\Milestones', $milestones);

        $data = $milestones->data();

        $this->assertFalse($data->cscMet);
        $this->assertFalse($data->rcMet);
        $this->assertFalse($data->rcOrCscMet);
    }

    /** @test */
    public function return_false_for_all_values_using_st_adolescent_algorithm_and_2_ratings()
    {
        $ratings = new RatingCollection;

        $ratings->add(0, '2020-01-16', 20.1);
        $ratings->add(0, '2020-01-16', 22.2);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor(1, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent', $algorithm);

        $milestones = new Milestones($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\Milestones', $milestones);

        $data = $milestones->data();

        $this->assertFalse($data->cscMet);
        $this->assertFalse($data->rcMet);
        $this->assertFalse($data->rcOrCscMet);
    }

    /** @test */
    public function return_true_for_rcMet_using_st_adolescent_algorithm_and_2_ratings()
    {
        $ratings = new RatingCollection;

        // Reliable Change is exactly 6.0
        $ratings->add(0, '2020-01-16', 20.1);
        $ratings->add(0, '2020-01-16', 26.1);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor(1, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent', $algorithm);

        $milestones = new Milestones($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\Milestones', $milestones);

        $data = $milestones->data();

        $this->assertFalse($data->cscMet);
        $this->assertTrue($data->rcMet);
        $this->assertTrue($data->rcOrCscMet);
    }

    /** @test */
    public function return_true_for_rcMet_using_st_adolescent_algorithm_and_3_ratings()
    {
        $ratings = new RatingCollection;

        // Reliable Change is exactly 7.0
        $ratings->add(0, '2020-01-16', 20.1);
        $ratings->add(0, '2020-01-16', 26.1);
        $ratings->add(0, '2020-01-16', 27.1);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor(1, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent', $algorithm);

        $milestones = new Milestones($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\Milestones', $milestones);

        $data = $milestones->data();

        $this->assertFalse($data->cscMet);
        $this->assertTrue($data->rcMet);
        $this->assertTrue($data->rcOrCscMet);
    }

    /** @test */
    public function return_false_for_cscMet_using_st_adolescent_algorithm_when_last_rating_equals_clinical_cutoff()
    {
        $ratings = new RatingCollection;

        // Reliable Change is exactly 7.0
        $ratings->add(0, '2020-01-16', 20.0);
        $ratings->add(0, '2020-01-16', 24.0);
        $ratings->add(0, '2020-01-16', 28.0);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor(1, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent', $algorithm);

        $milestones = new Milestones($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\Milestones', $milestones);

        $data = $milestones->data();

        $this->assertFalse($data->cscMet);
        $this->assertTrue($data->rcMet);
        $this->assertTrue($data->rcOrCscMet);
    }

    /** @test */
    public function return_true_for_cscMet_using_st_adolescent_algorithm_and_2_ratings_with_csc()
    {
        $ratings = new RatingCollection;

        // Reliable Change is exactly 8.1, but the last score is above the clinical cutoff.
        $ratings->add(0, '2020-01-16', 20.0);
        $ratings->add(0, '2020-01-16', 28.1);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor(1, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent', $algorithm);

        $milestones = new Milestones($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\Milestones', $milestones);

        $data = $milestones->data();

        $this->assertTrue($data->cscMet);
        $this->assertFalse($data->rcMet);
        $this->assertTrue($data->rcOrCscMet);
    }

    /** @test */
    public function return_true_for_rcMet_using_adult_algorithms_and_last_rating_equals_clinical_cutoff()
    {
        $ratings = new RatingCollection;

        // Reliable Change is exactly 6.0.
        $ratings->add(0, '2020-01-16', 19.0);
        $ratings->add(0, '2020-01-16', 25.0);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor(2, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdult', $algorithm);

        $milestones = new Milestones($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\Milestones', $milestones);

        $data = $milestones->data();

        $this->assertFalse($data->cscMet);
        $this->assertTrue($data->rcMet);
        $this->assertTrue($data->rcOrCscMet);

        $ratings->add(0, '2020-01-16', 22.0);
        $ratings->add(0, '2020-01-16', 21.0);
        $ratings->add(0, '2020-01-16', 12.0);
        $ratings->add(0, '2020-01-16', 11.0);
        $ratings->add(0, '2020-01-16', 22.0);
        $ratings->add(0, '2020-01-16', 21.0);
        $ratings->add(0, '2020-01-16', 12.0);
        $ratings->add(0, '2020-01-16', 25.0);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor(2, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\LongTerm\LongTermAdult', $algorithm);

        $milestones = new Milestones($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\Milestones', $milestones);

        $data = $milestones->data();

        $this->assertFalse($data->cscMet);
        $this->assertTrue($data->rcMet);
        $this->assertTrue($data->rcOrCscMet);
    }

    /** @test */
    public function return_true_for_cscMet_using_adult_algorithms_for_ratings_with_csc()
    {
        $ratings = new RatingCollection;

        // Reliable Change is exactly 6.0.
        $ratings->add(0, '2020-01-16', 19.0);
        $ratings->add(0, '2020-01-16', 25.1);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor(2, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdult', $algorithm);

        $milestones = new Milestones($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\Milestones', $milestones);

        $data = $milestones->data();

        $this->assertTrue($data->cscMet);
        $this->assertFalse($data->rcMet);
        $this->assertTrue($data->rcOrCscMet);

        $ratings->add(0, '2020-01-16', 22.0);
        $ratings->add(0, '2020-01-16', 21.0);
        $ratings->add(0, '2020-01-16', 12.0);
        $ratings->add(0, '2020-01-16', 11.0);
        $ratings->add(0, '2020-01-16', 22.0);
        $ratings->add(0, '2020-01-16', 21.0);
        $ratings->add(0, '2020-01-16', 12.0);
        $ratings->add(0, '2020-01-16', 25.1);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor(2, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\LongTerm\LongTermAdult', $algorithm);

        $milestones = new Milestones($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\Milestones', $milestones);

        $data = $milestones->data();

        $this->assertTrue($data->cscMet);
        $this->assertFalse($data->rcMet);
        $this->assertTrue($data->rcOrCscMet);
    }

    /** @test */
    public function return_false_for_rcMet_using_st_adolescent_algorithm_and_2_ratings_with_less_than_6_change()
    {
        $ratings = new RatingCollection;

        // Change is only 2.1.  No Reliable Change or Clinically Significant Change.
        $ratings->add(0, '2020-01-16', 20.1);
        $ratings->add(0, '2020-01-16', 22.2);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor(1, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent', $algorithm);

        $milestones = new Milestones($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\Milestones', $milestones);

        $data = $milestones->data();

        $this->assertFalse($data->cscMet);
        $this->assertFalse($data->rcMet);
        $this->assertFalse($data->rcOrCscMet);
    }

    /** @test */
    public function returns_true_for_rcMet_using_st_adolescent_algorithm_and_2_ratings_with_6_change()
    {
        $ratings = new RatingCollection;

        // Change is 6.0.  Reliable Change is met.
        $ratings->add(0, '2020-01-16', 20.1);
        $ratings->add(0, '2020-01-16', 26.1);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor(1, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent', $algorithm);

        $milestones = new Milestones($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\Milestones', $milestones);

        $data = $milestones->data();

        $this->assertFalse($data->cscMet);
        $this->assertTrue($data->rcMet);
        $this->assertTrue($data->rcOrCscMet);
    }

    /** @test */
    public function returns_true_for_rcMet_using_st_adolescent_algorithm_and_2_ratings_with_greater_6_change()
    {
        $ratings = new RatingCollection;

        // Change is 7.0.  Reliable Change is met.
        $ratings->add(0, '2020-01-16', 20.1);
        $ratings->add(0, '2020-01-16', 27.1);

        $manager = new AlgorithmManager;
        $algorithm = $manager->getFor(1, $ratings->count());
        $this->assertInstanceOf('Bluewing\Algorithms2015\ShortTerm\ShortTermAdolescent', $algorithm);

        $milestones = new Milestones($algorithm, $ratings);
        $this->assertInstanceOf('Bluewing\Progress\Milestones', $milestones);

        $data = $milestones->data();

        $this->assertFalse($data->cscMet);
        $this->assertTrue($data->rcMet);
        $this->assertTrue($data->rcOrCscMet);
    }
}