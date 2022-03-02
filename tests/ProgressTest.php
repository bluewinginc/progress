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
    public function it_returns_a_json_string_when_the_toJson_method_is_called()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->add(score: 1.2);
        $ratings->add(score: 2.6);
        $ratings->add(score: 22.0);

        $progress = new Progress($rater, $ratings);

        $this->assertIsString($progress->data()->toJson());
    }

    /** @test */
    public function it_returns_the_same_rater_data_using_any_output_method()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->add(score: 1.2);
        $ratings->add(score: 2.6);
        $ratings->add(score: 22.0);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();
        $a = $d->toArray();
        $j = json_decode($d->toJson());

        $key = 'rater';

        // 2 properties
        $this->assertEquals($d->rater->data()->ageGroup, $a[$key]['ageGroup']);
        $this->assertEquals($d->rater->data()->ageGroup, $j->rater->ageGroup);

        $this->assertEquals($d->rater->data()->excludeFromStats, $a[$key]['excludeFromStats']);
        $this->assertEquals($d->rater->data()->excludeFromStats, $j->rater->excludeFromStats);
    }

    /** @test */
    public function it_returns_the_same_ratings_using_any_output_method()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->addScores([1.2, 2.6, 22.0]);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();
        $a = $d->toArray();
        $j = json_decode($d->toJson(), true);   // for this test only, convert it to an associative array.

        $key = 'ratings';

        $this->assertCount($d->ratings->count(), $a[$key]);
        $this->assertCount($d->ratings->count(), $j[$key]);

        // 1 property (compare the entire array of ratings)
        $this->assertEquals($d->ratings->items(true), $a[$key]);
        $this->assertEquals($d->ratings->items(true), $j[$key]);
    }

    /** @test */
    public function it_returns_the_first_rating_using_any_output_method()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->addScores([1.2, 2.6, 22.0]);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();
        $a = $d->toArray();
        $j = json_decode($d->toJson());

        $key = 'firstRating';

        // 3 properties
        $this->assertEquals($d->firstRating->data()->id, $a[$key]['id']);
        $this->assertEquals($d->firstRating->data()->id, $j->firstRating->id);

        $this->assertEquals($d->firstRating->data()->dateCompleted, $a[$key]['dateCompleted']);
        $this->assertEquals($d->firstRating->data()->dateCompleted, $j->firstRating->dateCompleted);

        $this->assertEquals($d->firstRating->data()->score, $a[$key]['score']);
        $this->assertEquals($d->firstRating->data()->score, $j->firstRating->score);
    }

    /** @test */
    public function it_returns_the_last_rating_using_any_output_method()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->addScores([1.2, 2.6, 22.0]);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();
        $a = $d->toArray();
        $j = json_decode($d->toJson());

        $key = 'lastRating';

        // 3 properties
        $this->assertEquals($d->lastRating->data()->id, $a[$key]['id']);
        $this->assertEquals($d->lastRating->data()->id, $j->lastRating->id);

        $this->assertEquals($d->lastRating->data()->dateCompleted, $a[$key]['dateCompleted']);
        $this->assertEquals($d->lastRating->data()->dateCompleted, $j->lastRating->dateCompleted);

        $this->assertEquals($d->lastRating->data()->score, $a[$key]['score']);
        $this->assertEquals($d->lastRating->data()->score, $j->lastRating->score);
    }

    /** @test */
    public function it_returns_the_rating_change_using_any_output_method()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->addScores([1.2, 2.6, 22.0]);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();
        $a = $d->toArray();
        $j = json_decode($d->toJson());

        $key = 'ratingChange';

        // 1 properties
        $this->assertEquals($d->ratingChange, $a[$key]);
        $this->assertEquals($d->ratingChange, $j->ratingChange);
    }

    /** @test */
    public function it_returns_the_effectSize_using_any_output_method()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->addScores([1.2, 2.6, 22.0]);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();
        $a = $d->toArray();
        $j = json_decode($d->toJson());

        $key = 'effectSize';

        // 1 properties
        $this->assertEquals($d->effectSize, $a[$key]);
        $this->assertEquals($d->effectSize, $j->effectSize);
    }

    /** @test */
    public function it_returns_the_same_algorithm_data_using_any_output_method()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->addScores([1.2, 2.6, 22.0]);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();
        $a = $d->toArray();
        $j = json_decode($d->toJson());

        $key = 'algorithm';

        // 15 properties
        $this->assertEquals($d->algorithm->clinicalCutoff, $a[$key]['clinicalCutoff']);
        $this->assertEquals($d->algorithm->clinicalCutoff, $j->algorithm->clinicalCutoff);

        $this->assertEquals($d->algorithm->cubicByIntake, $a[$key]['cubicByIntake']);
        $this->assertEquals($d->algorithm->cubicByIntake, $j->algorithm->cubicByIntake);

        $this->assertEquals($d->algorithm->cubicMean, $a[$key]['cubicMean']);
        $this->assertEquals($d->algorithm->cubicMean, $j->algorithm->cubicMean);

        $this->assertEquals($d->algorithm->flattenMeeting, $a[$key]['flattenMeeting']);
        $this->assertEquals($d->algorithm->flattenMeeting, $j->algorithm->flattenMeeting);

        $this->assertEquals($d->algorithm->info, $a[$key]['info']);
        $this->assertEquals($d->algorithm->info, $j->algorithm->info);

        $this->assertEquals($d->algorithm->intake, $a[$key]['intake']);
        $this->assertEquals($d->algorithm->intake, $j->algorithm->intake);

        $this->assertEquals($d->algorithm->interceptMean, $a[$key]['interceptMean']);
        $this->assertEquals($d->algorithm->interceptMean, $j->algorithm->interceptMean);

        $this->assertEquals($d->algorithm->linearByIntake, $a[$key]['linearByIntake']);
        $this->assertEquals($d->algorithm->linearByIntake, $j->algorithm->linearByIntake);

        $this->assertEquals($d->algorithm->linearMean, $a[$key]['linearMean']);
        $this->assertEquals($d->algorithm->linearMean, $j->algorithm->linearMean);

        $this->assertEquals($d->algorithm->maxMeetings, $a[$key]['maxMeetings']);
        $this->assertEquals($d->algorithm->maxMeetings, $j->algorithm->maxMeetings);

        $this->assertEquals($d->algorithm->minMeetings, $a[$key]['minMeetings']);
        $this->assertEquals($d->algorithm->minMeetings, $j->algorithm->minMeetings);

        $this->assertEquals($d->algorithm->quadraticByIntake, $a[$key]['quadraticByIntake']);
        $this->assertEquals($d->algorithm->quadraticByIntake, $j->algorithm->quadraticByIntake);

        $this->assertEquals($d->algorithm->quadraticMean, $a[$key]['quadraticMean']);
        $this->assertEquals($d->algorithm->quadraticMean, $j->algorithm->quadraticMean);

        $this->assertEquals($d->algorithm->reliableChangeIndex, $a[$key]['reliableChangeIndex']);
        $this->assertEquals($d->algorithm->reliableChangeIndex, $j->algorithm->reliableChangeIndex);

        $this->assertEquals($d->algorithm->standardDeviation, $a[$key]['standardDeviation']);
        $this->assertEquals($d->algorithm->standardDeviation, $j->algorithm->standardDeviation);
    }

    /** @test */
    public function it_returns_the_same_st_algorithm_data_using_any_output_method()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->add(score: 1.2);
        $ratings->add(score: 2.6);
        $ratings->add(score: 22.0);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();
        $a = $d->toArray();
        $j = json_decode($d->toJson());

        $key = 'algorithmShortTerm';

        // 15 properties
        $this->assertEquals($d->algorithmShortTerm->clinicalCutoff, $a[$key]['clinicalCutoff']);
        $this->assertEquals($d->algorithmShortTerm->clinicalCutoff, $j->algorithmShortTerm->clinicalCutoff);

        $this->assertEquals($d->algorithmShortTerm->cubicByIntake, $a[$key]['cubicByIntake']);
        $this->assertEquals($d->algorithmShortTerm->cubicByIntake, $j->algorithmShortTerm->cubicByIntake);

        $this->assertEquals($d->algorithmShortTerm->cubicMean, $a[$key]['cubicMean']);
        $this->assertEquals($d->algorithmShortTerm->cubicMean, $j->algorithmShortTerm->cubicMean);

        $this->assertEquals($d->algorithmShortTerm->flattenMeeting, $a[$key]['flattenMeeting']);
        $this->assertEquals($d->algorithmShortTerm->flattenMeeting, $j->algorithmShortTerm->flattenMeeting);

        $this->assertEquals($d->algorithmShortTerm->info, $a[$key]['info']);
        $this->assertEquals($d->algorithmShortTerm->info, $j->algorithmShortTerm->info);

        $this->assertEquals($d->algorithmShortTerm->intake, $a[$key]['intake']);
        $this->assertEquals($d->algorithmShortTerm->intake, $j->algorithmShortTerm->intake);

        $this->assertEquals($d->algorithmShortTerm->interceptMean, $a[$key]['interceptMean']);
        $this->assertEquals($d->algorithmShortTerm->interceptMean, $j->algorithmShortTerm->interceptMean);

        $this->assertEquals($d->algorithmShortTerm->linearByIntake, $a[$key]['linearByIntake']);
        $this->assertEquals($d->algorithmShortTerm->linearByIntake, $j->algorithmShortTerm->linearByIntake);

        $this->assertEquals($d->algorithmShortTerm->linearMean, $a[$key]['linearMean']);
        $this->assertEquals($d->algorithmShortTerm->linearMean, $j->algorithmShortTerm->linearMean);

        $this->assertEquals($d->algorithmShortTerm->maxMeetings, $a[$key]['maxMeetings']);
        $this->assertEquals($d->algorithmShortTerm->maxMeetings, $j->algorithmShortTerm->maxMeetings);

        $this->assertEquals($d->algorithmShortTerm->minMeetings, $a[$key]['minMeetings']);
        $this->assertEquals($d->algorithmShortTerm->minMeetings, $j->algorithmShortTerm->minMeetings);

        $this->assertEquals($d->algorithmShortTerm->quadraticByIntake, $a[$key]['quadraticByIntake']);
        $this->assertEquals($d->algorithmShortTerm->quadraticByIntake, $j->algorithmShortTerm->quadraticByIntake);

        $this->assertEquals($d->algorithmShortTerm->quadraticMean, $a[$key]['quadraticMean']);
        $this->assertEquals($d->algorithmShortTerm->quadraticMean, $j->algorithmShortTerm->quadraticMean);

        $this->assertEquals($d->algorithmShortTerm->reliableChangeIndex, $a[$key]['reliableChangeIndex']);
        $this->assertEquals($d->algorithmShortTerm->reliableChangeIndex, $j->algorithmShortTerm->reliableChangeIndex);

        $this->assertEquals($d->algorithmShortTerm->standardDeviation, $a[$key]['standardDeviation']);
        $this->assertEquals($d->algorithmShortTerm->standardDeviation, $j->algorithmShortTerm->standardDeviation);
    }

    /** @test */
    public function it_returns_the_same_etr_meeting_target_data_using_any_output_method()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->add(score: 1.2);
        $ratings->add(score: 2.6);
        $ratings->add(score: 22.0);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();
        $a = $d->toArray();
        $j = json_decode($d->toJson());

        $key = 'etrMtgTarget';

        // 6 properties
        $this->assertEquals($d->etrMtgTarget->expectedChange, $a[$key]['expectedChange']);
        $this->assertEquals($d->etrMtgTarget->expectedChange, $j->etrMtgTarget->expectedChange);

        $this->assertEquals($d->etrMtgTarget->met, $a[$key]['met']);
        $this->assertEquals($d->etrMtgTarget->met, $j->etrMtgTarget->met);

        $this->assertEquals($d->etrMtgTarget->metPercent, $a[$key]['metPercent']);
        $this->assertEquals($d->etrMtgTarget->metPercent, $j->etrMtgTarget->metPercent);

        $this->assertEquals($d->etrMtgTarget->metPercent50, $a[$key]['metPercent50']);
        $this->assertEquals($d->etrMtgTarget->metPercent50, $j->etrMtgTarget->metPercent50);

        $this->assertEquals($d->etrMtgTarget->metPercent67, $a[$key]['metPercent67']);
        $this->assertEquals($d->etrMtgTarget->metPercent67, $j->etrMtgTarget->metPercent67);

        $this->assertEquals($d->etrMtgTarget->value, $a[$key]['value']);
        $this->assertEquals($d->etrMtgTarget->value, $j->etrMtgTarget->value);
    }

    /** @test */
    public function it_returns_the_same_etr_target_data_using_any_output_method()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->add(score: 1.2);
        $ratings->add(score: 2.6);
        $ratings->add(score: 22.0);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();
        $a = $d->toArray();
        $j = json_decode($d->toJson());

        $key = 'etrTarget';

        // 6 properties
        $this->assertEquals($d->etrTarget->expectedChange, $a[$key]['expectedChange']);
        $this->assertEquals($d->etrTarget->expectedChange, $j->etrTarget->expectedChange);

        $this->assertEquals($d->etrTarget->met, $a[$key]['met']);
        $this->assertEquals($d->etrTarget->met, $j->etrTarget->met);

        $this->assertEquals($d->etrTarget->metPercent, $a[$key]['metPercent']);
        $this->assertEquals($d->etrTarget->metPercent, $j->etrTarget->metPercent);

        $this->assertEquals($d->etrTarget->metPercent50, $a[$key]['metPercent50']);
        $this->assertEquals($d->etrTarget->metPercent50, $j->etrTarget->metPercent50);

        $this->assertEquals($d->etrTarget->metPercent67, $a[$key]['metPercent67']);
        $this->assertEquals($d->etrTarget->metPercent67, $j->etrTarget->metPercent67);

        $this->assertEquals($d->etrTarget->value, $a[$key]['value']);
        $this->assertEquals($d->etrTarget->value, $j->etrTarget->value);
    }

    /** @test */
    public function it_returns_the_same_milestones_data_using_any_output_method()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->add(score: 1.2);
        $ratings->add(score: 2.6);
        $ratings->add(score: 22.0);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();
        $a = $d->toArray();
        $j = json_decode($d->toJson());

        $key = 'milestones';

        // 3 properties
        $this->assertEquals($d->milestones->cscMet, $a[$key]['cscMet']);
        $this->assertEquals($d->milestones->cscMet, $j->milestones->cscMet);

        $this->assertEquals($d->milestones->rcMet, $a[$key]['rcMet']);
        $this->assertEquals($d->milestones->rcMet, $j->milestones->rcMet);

        $this->assertEquals($d->milestones->rcOrCscMet, $a[$key]['rcOrCscMet']);
        $this->assertEquals($d->milestones->rcOrCscMet, $j->milestones->rcOrCscMet);
    }

    /** @test */
    public function it_returns_the_same_validity_indicators_data_using_any_output_method()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->add(score: 1.2);
        $ratings->add(score: 2.6);
        $ratings->add(score: 22.0);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();
        $a = $d->toArray();
        $j = json_decode($d->toJson());

        $key = 'validityIndicators';
        $subKey = 'clinicalCutoff';

        // 8 properties
        $this->assertEquals($d->validityIndicators->clinicalCutoff->firstRatingScore, $a[$key][$subKey]['firstRatingScore']);
        $this->assertEquals($d->validityIndicators->clinicalCutoff->firstRatingScore, $j->validityIndicators->clinicalCutoff->firstRatingScore);

        $this->assertEquals($d->validityIndicators->clinicalCutoff->isAbove, $a[$key][$subKey]['isAbove']);
        $this->assertEquals($d->validityIndicators->clinicalCutoff->isAbove, $j->validityIndicators->clinicalCutoff->isAbove);

        $this->assertEquals($d->validityIndicators->clinicalCutoff->value, $a[$key][$subKey]['value']);
        $this->assertEquals($d->validityIndicators->clinicalCutoff->value, $j->validityIndicators->clinicalCutoff->value);

        $this->assertEquals($d->validityIndicators->firstRatingAbove32, $a[$key]['firstRatingAbove32']);
        $this->assertEquals($d->validityIndicators->firstRatingAbove32, $j->validityIndicators->firstRatingAbove32);

        $subKey = 'sawtoothPattern';

        $this->assertEquals($d->validityIndicators->sawtoothPattern->directionChanges, $a[$key][$subKey]['directionChanges']);
        $this->assertEquals($d->validityIndicators->sawtoothPattern->directionChanges, $j->validityIndicators->sawtoothPattern->directionChanges);

        $this->assertEquals($d->validityIndicators->sawtoothPattern->has, $a[$key][$subKey]['has']);
        $this->assertEquals($d->validityIndicators->sawtoothPattern->has, $j->validityIndicators->sawtoothPattern->has);

        $this->assertEquals($d->validityIndicators->sawtoothPattern->teeth, $a[$key][$subKey]['teeth']);
        $this->assertEquals($d->validityIndicators->sawtoothPattern->teeth, $j->validityIndicators->sawtoothPattern->teeth);

        $this->assertEquals($d->validityIndicators->zeroOrOneMeetings, $a[$key]['zeroOrOneMeetings']);
        $this->assertEquals($d->validityIndicators->zeroOrOneMeetings, $j->validityIndicators->zeroOrOneMeetings);
    }

    /** @test */
    public function it_returns_the_same_exclusions_data_using_any_output_method()
    {
        $rater = new Rater(1);
        $ratings = new RatingCollection;

        $ratings->add(score: 1.2);
        $ratings->add(score: 2.6);
        $ratings->add(score: 22.0);

        $progress = new Progress($rater, $ratings);

        $d = $progress->data();
        $a = $d->toArray();
        $j = json_decode($d->toJson());

        $key = 'exclusions';

        // 5 properties
        $this->assertEquals($d->exclusions->excluded, $a[$key]['excluded']);
        $this->assertEquals($d->exclusions->excluded, $j->exclusions->excluded);

        $this->assertEquals($d->exclusions->userExcluded, $a[$key]['userExcluded']);
        $this->assertEquals($d->exclusions->userExcluded, $j->exclusions->userExcluded);

        $this->assertEquals($d->exclusions->firstRatingAbove32, $a[$key]['firstRatingAbove32']);
        $this->assertEquals($d->exclusions->firstRatingAbove32, $j->exclusions->firstRatingAbove32);

        $this->assertEquals($d->exclusions->zeroOrOneMeetings, $a[$key]['zeroOrOneMeetings']);
        $this->assertEquals($d->exclusions->zeroOrOneMeetings, $j->exclusions->zeroOrOneMeetings);

        $this->assertEquals($d->exclusions->included, $a[$key]['included']);
        $this->assertEquals($d->exclusions->included, $j->exclusions->included);
    }
}