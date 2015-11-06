<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class ExerciseEntriesTest
 */
class ExerciseEntriesTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_lists_the_exercise_entries_for_one_day()
    {
        $this->logInUser();

        $date = Carbon::today()->format('Y-m-d');

        $response = $this->apiCall('GET', '/api/exerciseEntries/' . $date);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('exercise', $content[0]);
        $this->assertArrayHasKey('name', $content[0]['exercise']);
        $this->assertArrayHasKey('id', $content[0]['exercise']);
        $this->assertArrayHasKey('description', $content[0]['exercise']);
        $this->assertArrayHasKey('step_number', $content[0]['exercise']);

        $this->assertArrayHasKey('unit', $content[0]);
        $this->assertArrayHasKey('id', $content[0]['unit']);
        $this->assertArrayHasKey('name', $content[0]['unit']);

        $this->assertArrayHasKey('default_unit_id', $content[0]);
        $this->assertArrayHasKey('sets', $content[0]);
        $this->assertArrayHasKey('total', $content[0]);
        $this->assertArrayHasKey('quantity', $content[0]);

        $this->assertEquals(1, $content[0]['exercise']['id']);
        $this->assertEquals('kneeling pushups', $content[0]['exercise']['name']);
        $this->assertEquals('1.00', $content[0]['exercise']['step_number']);

        /**
         * @VP:
         * Can I exclude the description (a faker word) here so it passes?
         * So I can use this code instead of the above code.
         */
//        $this->assertEquals([
//            'id' => 1,
//            'name' => 'kneeling pushups',
//            'step_number' => '1.00'
//        ], $content[0]['exercise']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'reps'
        ], $content[0]['unit']);

        $this->assertEquals(1, $content[0]['default_unit_id']);
        $this->assertEquals(2, $content[0]['sets']);
        $this->assertEquals(10, $content[0]['total']);
        $this->assertEquals(5, $content[0]['quantity']);
        $this->assertCount(2, $content);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_add_a_new_exercise_entry()
    {
        $this->logInUser();

        $entry = [
            'date' => Carbon::today()->format('Y-m-d'),
            'exercise_id' => 1,
            'quantity' => 5,
            'unit_id' => 1
        ];

        $response = $this->call('POST', '/api/exerciseEntries', $entry);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//        dd($content[0]);

        $this->assertArrayHasKey('exercise', $content[0]);
        $this->assertArrayHasKey('id', $content[0]['exercise']);
        $this->assertArrayHasKey('name', $content[0]['exercise']);
        $this->assertArrayHasKey('description', $content[0]['exercise']);
        $this->assertArrayHasKey('step_number', $content[0]['exercise']);
        $this->assertArrayHasKey('default_unit_id', $content[0]['exercise']);

        $this->assertArrayHasKey('unit', $content[0]);
        $this->assertArrayHasKey('id', $content[0]['unit']);
        $this->assertArrayHasKey('name', $content[0]['unit']);

        $this->assertArrayHasKey('sets', $content[0]);
        $this->assertArrayHasKey('total', $content[0]);
        $this->assertArrayHasKey('quantity', $content[0]);

        $this->assertEquals(1, $content[0]['exercise']['id']);
        $this->assertEquals('kneeling pushups', $content[0]['exercise']['name']);
        $this->assertEquals('1.00', $content[0]['exercise']['step_number']);

        $this->assertEquals(1, $content[0]['unit']['id']);
        $this->assertEquals('reps', $content[0]['unit']['name']);

        $this->assertEquals(1, $content[0]['exercise']['default_unit_id']);
        $this->assertEquals(3, $content[0]['sets']);
        $this->assertEquals(15, $content[0]['total']);
        $this->assertEquals(5, $content[0]['quantity']);
        $this->assertCount(2, $content);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_add_an_exercise_set()
    {
        $this->logInUser();

        $entry = [
            'date' => Carbon::today()->format('Y-m-d'),
            'exercise_id' => 1,
            'exerciseSet' => true
        ];

        $response = $this->call('POST', '/api/exerciseEntries', $entry);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('exercise', $content[0]);
        $this->assertArrayHasKey('id', $content[0]['exercise']);
        $this->assertArrayHasKey('name', $content[0]['exercise']);
        $this->assertArrayHasKey('description', $content[0]['exercise']);
        $this->assertArrayHasKey('step_number', $content[0]['exercise']);
        $this->assertArrayHasKey('default_unit_id', $content[0]['exercise']);

        $this->assertArrayHasKey('unit', $content[0]);
        $this->assertArrayHasKey('id', $content[0]['unit']);
        $this->assertArrayHasKey('name', $content[0]['unit']);

        $this->assertArrayHasKey('sets', $content[0]);
        $this->assertArrayHasKey('total', $content[0]);
        $this->assertArrayHasKey('quantity', $content[0]);

        $this->assertEquals(1, $content[0]['exercise']['id']);
        $this->assertEquals('kneeling pushups', $content[0]['exercise']['name']);
        $this->assertEquals('1.00', $content[0]['exercise']['step_number']);
        $this->assertEquals(1, $content[0]['exercise']['default_unit_id']);

        $this->assertEquals(1, $content[0]['unit']['id']);
        $this->assertEquals('reps', $content[0]['unit']['name']);

        $this->assertEquals(3, $content[0]['sets']);
        $this->assertEquals(15, $content[0]['total']);
        $this->assertEquals(5, $content[0]['quantity']);
        $this->assertCount(2, $content);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
//    public function it_can_update_an_exercise_entry()
//    {
//        $this->logInUser();
//
//        $unit = Unit::forCurrentUser()->where('for', 'food')->first();
//
//        $response = $this->call('PUT', '/api/foodUnits/'.$unit->id, [
//            'name' => 'numbat'
//        ]);
//        $content = json_decode($response->getContent(), true)['data'];
//
//        $this->assertArrayHasKey('id', $content);
//        $this->assertArrayHasKey('name', $content);
//        $this->assertArrayHasKey('for', $content);
//
//        $this->assertEquals('numbat', $content['name']);
//
//        $this->assertEquals(200, $response->getStatusCode());
//    }

    /**
     * @test
     * @return void
     */
//    public function it_can_delete_an_exercise_entry()
//    {
//        $this->logInUser();
//
//        $exercise = new Exercise([
//            'name' => 'echidna'
//        ]);
//
//        $exercise->user()->associate($this->user);
//        $exercise->save();
//
//        $this->seeInDatabase('exercises', ['name' => 'echidna']);
//
//        $response = $this->call('DELETE', '/api/exercises/'.$exercise->id);
//        $this->assertEquals(204, $response->getStatusCode());
//        $this->missingFromDatabase('exercises', ['name' => 'echidna']);
//
//        $response = $this->call('DELETE', '/api/units/0');
//        $this->assertEquals(404, $response->getStatusCode());
//    }
}
