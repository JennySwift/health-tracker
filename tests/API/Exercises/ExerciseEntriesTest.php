<?php

use App\Models\Exercises\Entry;
use App\Models\Exercises\Exercise;
use App\Models\Units\Unit;
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

        $this->checkExerciseEntryKeysExist($content[0]);

        $this->assertEquals(1, $content[0]['exercise']['data']['id']);
        $this->assertEquals('kneeling pushups', $content[0]['exercise']['data']['name']);
        $this->assertEquals('1.00', $content[0]['exercise']['data']['stepNumber']);
        $this->assertEquals(1, $content[0]['exercise']['data']['defaultUnit']['data']['id']);

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
    public function it_gets_entries_for_a_specific_exercise_and_date_and_unit()
    {
        $this->logInUser();

        $date = Carbon::today()->format('Y-m-d');
        $response = $this->call('GET', '/api/exerciseEntries/specificExerciseAndDateAndUnit?exercise_id=1&exercise_unit_id=1&date=' . $date);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseEntryKeysExist($content[0]);
        
        foreach ($content as $entry) {
            $this->assertEquals($date, $entry['date']);
            $this->assertEquals(1, $entry['unit']['id']);
            $this->assertEquals(1, $entry['exercise']['data']['id']);
        }

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
//        dd($content);

        $this->checkExerciseEntryKeysExist($content[0]);

        $this->assertEquals(1, $content[0]['exercise']['data']['id']);
        $this->assertEquals('kneeling pushups', $content[0]['exercise']['data']['name']);
        $this->assertEquals('1.00', $content[0]['exercise']['data']['stepNumber']);

        $this->assertEquals(1, $content[0]['unit']['id']);
        $this->assertEquals('reps', $content[0]['unit']['name']);

        $this->assertEquals(1, $content[0]['exercise']['data']['defaultUnit']['data']['id']);
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

        $this->checkExerciseEntryKeysExist($content[0]);

        $this->assertEquals(1, $content[0]['exercise']['data']['id']);
        $this->assertEquals('kneeling pushups', $content[0]['exercise']['data']['name']);
        $this->assertEquals('1.00', $content[0]['exercise']['data']['stepNumber']);
        $this->assertEquals(1, $content[0]['exercise']['data']['defaultUnit']['data']['id']);

        $this->assertEquals(1, $content[0]['unit']['id']);
        $this->assertEquals('reps', $content[0]['unit']['name']);

        $this->assertEquals(3, $content[0]['sets']);
        //2 * 5 reps from seeder, plus one set of 20 from the 'add set' function
        $this->assertEquals(30, $content[0]['total']);
        //Not sure why this should equal 5 since the last set added was 20
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
    public function it_can_delete_an_exercise_entry()
    {
        $this->logInUser();

        $date = Carbon::today()->format('Y-m-d');

        $entry = new Entry([
            'date' => $date,
            'quantity' => 501,
        ]);

        $entry->user()->associate($this->user);
        $entry->exercise()->associate(Exercise::find(1));
        $entry->unit()->associate(Unit::find(1));
        $entry->save();

        $this->seeInDatabase('exercise_entries', [
            'date' => $date,
            'exercise_id' => 1,
            'quantity' => 501,
            'exercise_unit_id' => 1
        ]);

        $response = $this->call('DELETE', '/api/exerciseEntries/'.$entry->id);
        $this->assertEquals(204, $response->getStatusCode());
        $this->missingFromDatabase('exercise_entries', [
            'date' => $date,
            'exercise_id' => 1,
            'quantity' => 501,
            'exercise_unit_id' => 1
        ]);

        $response = $this->call('DELETE', '/api/exerciseEntries/' . $entry->id);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
