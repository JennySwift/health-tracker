<?php

use App\Models\Menu\Entry;
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
//        dd($content);

        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('date', $content[0]);
        $this->assertArrayHasKey('quantity', $content[0]);
        $this->assertArrayHasKey('calories', $content[0]);
        $this->assertArrayHasKey('food', $content[0]);
        $this->assertArrayHasKey('unit', $content[0]);


        $this->assertEquals(1, $content[0]['id']);
        $this->assertEquals($date, $content[0]['date']);
        $this->assertEquals('5.00', $content[0]['quantity']);
        $this->assertEquals(25, $content[0]['calories']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'apple'
        ], $content[0]['food']);

        $this->assertEquals([
            'id' => 3,
            'name' => 'small'
        ], $content[0]['unit']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'delicious recipe'
        ], $content[1]['recipe']);

        $this->assertCount(2, $content);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_add_a_new_food_entry()
    {
        $this->logInUser();

        $entry = [
            'date' => '2010-05-01',
            'food_id' => 3,
            'quantity' => 4,
            'unit_id' => 2,
        ];

        $response = $this->call('POST', '/api/menuEntries', $entry);
        $content = json_decode($response->getContent(), true);
//dd($content);

        $this->checkFoodEntryKeysExist($content);

        $this->assertEquals('2010-05-01', $content['date']);
        $this->assertEquals(4, $content['quantity']);
        $this->assertEquals(2, $content['unit']['id']);
        $this->assertEquals(3, $content['food']['id']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * Just one food should be inserted into the database,
     * so that the store method is RESTful.
     * So lots of ajax requests will be made to insert
     * all the entries for the whole recipe.
     * @test
     * @return void
     */
    public function it_can_add_a_new_recipe_entry()
    {
        $this->logInUser();

        $entry = [
            'date' => '2010-05-01',
            'food_id' => 3,
            'recipe_id' => 2,
            'quantity' => 4,
            'unit_id' => 2,
        ];

        $response = $this->call('POST', '/api/menuEntries', $entry);
        $content = json_decode($response->getContent(), true);
//dd($content);

        $this->checkRecipeEntryKeysExist($content);

        $this->assertEquals('2010-05-01', $content['date']);
        $this->assertEquals(2, $content['recipe']['id']);
        $this->assertEquals(4, $content['quantity']);
        $this->assertEquals(2, $content['unit']['id']);
        $this->assertEquals(3, $content['food']['id']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

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
    public function it_can_delete_a_menu_entry()
    {
        DB::beginTransaction();
        $this->logInUser();

        $entry = Entry::first();

        $response = $this->call('DELETE', '/api/menuEntries/'.$entry->id);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', '/api/menuEntries/' . $entry->id);
        $this->assertEquals(404, $response->getStatusCode());

        DB::rollBack();
    }

}
