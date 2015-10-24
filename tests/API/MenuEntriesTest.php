<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class MenuEntriesTest
 */
class MenuEntriesTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_lists_the_menu_entries_for_one_day()
    {
        $this->logInUser();

        $date = Carbon::today()->format('Y-m-d');

        $response = $this->apiCall('GET', '/api/menuEntries/' . $date);
        $content = json_decode($response->getContent(), true);
        dd($content);

        /**
         * food_id
         * food_name
         * quantity
         * entry_id
         * unit_id
         * unit_name
         * recipe_id
         * recipe_name
         */

        $this->assertArrayHasKey('exercise_id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);
        $this->assertArrayHasKey('description', $content[0]);
        $this->assertArrayHasKey('step_number', $content[0]);
        $this->assertArrayHasKey('unit_id', $content[0]);
        $this->assertArrayHasKey('unit_name', $content[0]);
        $this->assertArrayHasKey('default_unit_id', $content[0]);
        $this->assertArrayHasKey('sets', $content[0]);
        $this->assertArrayHasKey('total', $content[0]);
        $this->assertArrayHasKey('quantity', $content[0]);

        $this->assertEquals(1, $content[0]['exercise_id']);
        $this->assertEquals('kneeling pushups', $content[0]['name']);
        $this->assertEquals('1.00', $content[0]['step_number']);
        $this->assertEquals(1, $content[0]['unit_id']);
        $this->assertEquals('reps', $content[0]['unit_name']);
        $this->assertEquals(1, $content[0]['default_unit_id']);
        $this->assertEquals(3, $content[0]['sets']);
        $this->assertEquals(15, $content[0]['total']);
        $this->assertEquals(5, $content[0]['quantity']);
        $this->assertCount(2, $content);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
//    public function it_can_add_a_new_menu_entry()
//    {
//        $this->logInUser();
//
//        $entry = [
//            'date' => Carbon::today()->format('Y-m-d'),
//            'exercise_id' => 1,
//            'quantity' => 5,
//            'unit_id' => 1
//        ];
//
//        $response = $this->call('POST', '/api/exerciseEntries', $entry);
//        $content = json_decode($response->getContent(), true);
//
//        $this->assertArrayHasKey('exercise_id', $content[0]);
//        $this->assertArrayHasKey('name', $content[0]);
//        $this->assertArrayHasKey('description', $content[0]);
//        $this->assertArrayHasKey('step_number', $content[0]);
//        $this->assertArrayHasKey('unit_id', $content[0]);
//        $this->assertArrayHasKey('unit_name', $content[0]);
//        $this->assertArrayHasKey('default_unit_id', $content[0]);
//        $this->assertArrayHasKey('sets', $content[0]);
//        $this->assertArrayHasKey('total', $content[0]);
//        $this->assertArrayHasKey('quantity', $content[0]);
//
//        $this->assertEquals(1, $content[0]['exercise_id']);
//        $this->assertEquals('kneeling pushups', $content[0]['name']);
//        $this->assertEquals('1.00', $content[0]['step_number']);
//        $this->assertEquals(1, $content[0]['unit_id']);
//        $this->assertEquals('reps', $content[0]['unit_name']);
//        $this->assertEquals(1, $content[0]['default_unit_id']);
//        $this->assertEquals(4, $content[0]['sets']);
//        $this->assertEquals(20, $content[0]['total']);
//        $this->assertEquals(5, $content[0]['quantity']);
//        $this->assertCount(2, $content);
//
//        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
//    }

    /**
     * @test
     * @return void
     */
//    public function it_can_update_a_menu_entry()
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
//    public function it_can_delete_a_menu_entry()
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
