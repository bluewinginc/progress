<?php

namespace Bluewing\Progress\Tests;

use Bluewing\Progress\Progress;
use Bluewing\Progress\Rater;
use Bluewing\Progress\RatingCollection;
use Bluewing\Progress\Structs\ProgressStruct;
use PHPUnit\Framework\TestCase;

class ProgressTest extends TestCase
{
    /** @test */
    public function it_returns_a_progress_class_when_instantiated_with_valid_data()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->add(score: 1.2);
        $ratings->add(score: 2.6);
        $ratings->add(score: 22.0);

        $progress = new Progress($rater, $ratings);

        $this->assertInstanceOf(Progress::class, $progress);
    }

    /** @test */
    public function it_returns_a_progress_struct_when_the_data_method_is_called()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->add(score: 1.2);
        $ratings->add(score: 2.6);
        $ratings->add(score: 22.0);

        $progress = new Progress($rater, $ratings);

        $this->assertInstanceOf(ProgressStruct::class, $progress->data());
    }

    /** @test */
    public function it_returns_an_array_when_the_toArray_method_is_called()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->add(score: 1.2);
        $ratings->add(score: 2.6);
        $ratings->add(score: 22.0);

        $progress = new Progress($rater, $ratings);

        $this->assertIsArray($progress->data()->toArray());
    }

    /** @test */
    public function it_returns_the_same_data_using_any_output_method()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->add(score: 1.2);
        $ratings->add(score: 2.6);
        $ratings->add(score: 22.0);
        $ratings->add(score: 15.9);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();
        $a = $d->toArray();

        // rater
        $key = 'rater';
        $this->assertEquals($d->rater->data()->ageGroup, $a[$key]['ageGroup']);
        $this->assertEquals($d->rater->data()->ageGroupAsString, $a[$key]['ageGroupAsString']);
        $this->assertEquals($d->rater->data()->excludeFromStats, $a[$key]['excludeFromStats']);

        // ratingsCount
        $this->assertEquals($d->ratingsCount, $a['ratingsCount']);

        // ratings
        $key = 'ratings';
        $this->assertEquals($d->ratings->items(true), $a[$key]);

        // firstRating
        $key = 'firstRating';
        $this->assertEquals($d->firstRating->data()->id, $a[$key]['id']);
        $this->assertEquals($d->firstRating->data()->dateCompleted, $a[$key]['dateCompleted']);
        $this->assertEquals($d->firstRating->data()->score, $a[$key]['score']);
        $this->assertEquals($d->firstRating->data()->scoreAsString, $a[$key]['scoreAsString']);

        // lastRating
        $key = 'lastRating';
        $this->assertEquals($d->lastRating->data()->id, $a[$key]['id']);
        $this->assertEquals($d->lastRating->data()->dateCompleted, $a[$key]['dateCompleted']);
        $this->assertEquals($d->lastRating->data()->score, $a[$key]['score']);
        $this->assertEquals($d->lastRating->data()->scoreAsString, $a[$key]['scoreAsString']);

        // ratingChange
        $this->assertEquals($d->ratingChange, $a['ratingChange']);
        $this->assertEquals($d->ratingChangeAsString, $a['ratingChangeAsString']);

        // effectSize
        $this->assertEquals($d->effectSize, $a['effectSize']);
        $this->assertEquals($d->effectSizeAsString, $a['effectSizeAsString']);

        // algorithm
        $key = 'algorithm';
        $this->assertEquals($d->algorithm->version, $a[$key]['version']);
        $this->assertEquals($d->algorithm->clinicalCutoff, $a[$key]['clinicalCutoff']);
        $this->assertEquals($d->algorithm->clinicalCutoffAsString, $a[$key]['clinicalCutoffAsString']);
        $this->assertEquals($d->algorithm->reliableChangeIndex, $a[$key]['reliableChangeIndex']);
        $this->assertEquals($d->algorithm->reliableChangeIndexAsString, $a[$key]['reliableChangeIndexAsString']);
        $this->assertEquals($d->algorithm->standardDeviation, $a[$key]['standardDeviation']);
        $this->assertEquals($d->algorithm->standardDeviationAsString, $a[$key]['standardDeviationAsString']);
        $this->assertEquals($d->algorithm->srsClinicalCutoff, $a[$key]['srsClinicalCutoff']);
        $this->assertEquals($d->algorithm->srsClinicalCutoffAsString, $a[$key]['srsClinicalCutoffAsString']);


        // algorithmShortTerm
        $key = 'algorithmShortTerm';
        $this->assertEquals($d->algorithmShortTerm->version, $a[$key]['version']);
        $this->assertEquals($d->algorithmShortTerm->clinicalCutoff, $a[$key]['clinicalCutoff']);
        $this->assertEquals($d->algorithmShortTerm->clinicalCutoffAsString, $a[$key]['clinicalCutoffAsString']);
        $this->assertEquals($d->algorithmShortTerm->reliableChangeIndex, $a[$key]['reliableChangeIndex']);
        $this->assertEquals($d->algorithmShortTerm->reliableChangeIndexAsString, $a[$key]['reliableChangeIndexAsString']);
        $this->assertEquals($d->algorithmShortTerm->standardDeviation, $a[$key]['standardDeviation']);
        $this->assertEquals($d->algorithmShortTerm->standardDeviationAsString, $a[$key]['standardDeviationAsString']);
        $this->assertEquals($d->algorithmShortTerm->srsClinicalCutoff, $a[$key]['srsClinicalCutoff']);
        $this->assertEquals($d->algorithmShortTerm->srsClinicalCutoffAsString, $a[$key]['srsClinicalCutoffAsString']);

        // etrMtgTarget
        $key = 'etrMtgTarget';
        $this->assertEquals($d->etrMtgTarget->expectedChange, $a[$key]['expectedChange']);
        $this->assertEquals($d->etrMtgTarget->expectedChangeAsString, $a[$key]['expectedChangeAsString']);
        $this->assertEquals($d->etrMtgTarget->met, $a[$key]['met']);
        $this->assertEquals($d->etrMtgTarget->metPercent, $a[$key]['metPercent']);
        $this->assertEquals($d->etrMtgTarget->metPercentAsString, $a[$key]['metPercentAsString']);
        $this->assertEquals($d->etrMtgTarget->metPercent50, $a[$key]['metPercent50']);
        $this->assertEquals($d->etrMtgTarget->metPercent67, $a[$key]['metPercent67']);
        $this->assertEquals($d->etrMtgTarget->value, $a[$key]['value']);
        $this->assertEquals($d->etrMtgTarget->valueAsString, $a[$key]['valueAsString']);

        // etrTarget
        $key = 'etrTarget';
        $this->assertEquals($d->etrTarget->expectedChange, $a[$key]['expectedChange']);
        $this->assertEquals($d->etrTarget->expectedChangeAsString, $a[$key]['expectedChangeAsString']);
        $this->assertEquals($d->etrTarget->met, $a[$key]['met']);
        $this->assertEquals($d->etrTarget->metPercent, $a[$key]['metPercent']);
        $this->assertEquals($d->etrTarget->metPercentAsString, $a[$key]['metPercentAsString']);
        $this->assertEquals($d->etrTarget->metPercent50, $a[$key]['metPercent50']);
        $this->assertEquals($d->etrTarget->metPercent67, $a[$key]['metPercent67']);
        $this->assertEquals($d->etrTarget->value, $a[$key]['value']);
        $this->assertEquals($d->etrTarget->valueAsString, $a[$key]['valueAsString']);

        // milestones
        $key = 'milestones';
        $this->assertEquals($d->milestones->cscMet, $a[$key]['cscMet']);
        $this->assertEquals($d->milestones->rcMet, $a[$key]['rcMet']);
        $this->assertEquals($d->milestones->rcOrCscMet, $a[$key]['rcOrCscMet']);

        //validityIndicators
        $key = 'validityIndicators';

        // clinicalCutoff
        $subKey = 'clinicalCutoff';
        $this->assertEquals($d->validityIndicators->clinicalCutoff->firstRatingScore, $a[$key][$subKey]['firstRatingScore']);
        $this->assertEquals($d->validityIndicators->clinicalCutoff->firstRatingScoreAsString, $a[$key][$subKey]['firstRatingScoreAsString']);
        $this->assertEquals($d->validityIndicators->clinicalCutoff->isAbove, $a[$key][$subKey]['isAbove']);
        $this->assertEquals($d->validityIndicators->clinicalCutoff->value, $a[$key][$subKey]['value']);
        $this->assertEquals($d->validityIndicators->clinicalCutoff->valueAsString, $a[$key][$subKey]['valueAsString']);

        // firstRatingAbove32
        $this->assertEquals($d->validityIndicators->firstRatingAbove32, $a[$key]['firstRatingAbove32']);

        // sawtoothPattern
        $subKey = 'sawtoothPattern';
        $this->assertEquals($d->validityIndicators->sawtoothPattern->directionChanges, $a[$key][$subKey]['directionChanges']);
        $this->assertEquals($d->validityIndicators->sawtoothPattern->has, $a[$key][$subKey]['has']);
        $this->assertEquals($d->validityIndicators->sawtoothPattern->teeth, $a[$key][$subKey]['teeth']);

        // zeroOrOneMeetings
        $this->assertEquals($d->validityIndicators->zeroOrOneMeetings, $a[$key]['zeroOrOneMeetings']);

        // exclusions
        $key = 'exclusions';
        $this->assertEquals($d->exclusions->excluded, $a[$key]['excluded']);
        $this->assertEquals($d->exclusions->userExcluded, $a[$key]['userExcluded']);
        $this->assertEquals($d->exclusions->firstRatingAbove32, $a[$key]['firstRatingAbove32']);
        $this->assertEquals($d->exclusions->zeroOrOneMeetings, $a[$key]['zeroOrOneMeetings']);
        $this->assertEquals($d->exclusions->included, $a[$key]['included']);
    }

    /** @test */
    public function it_returns_the_effectSize_as_a_string_to_two_decimal_points()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->addScores([31.1, 33.6, 31.9]);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();

        $this->assertEquals(0.1, $d->effectSize);
        $this->assertEquals('0.10', $d->effectSizeAsString);
    }
}