<?php

use App\Models\Units\Unit;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class FoodUnitsTest extends TestCase {

    use DatabaseTransactions;

    /**
     * It lists the food units for the user
     * @todo: check all units belong to the user?
     * @test
     * @return void
     */
    public function it_lists_all_food_units()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/foodUnits');
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkFoodUnitKeysExist($content[0]);

        $this->assertEquals(6, $content[1]['id']);
        $this->assertEquals('grams', $content[1]['name']);
        $this->assertEquals('food', $content[1]['for']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * It lists the food units for the user,
     * and for each unit, includes the calories for a specific food,
     * if the calories for that food exist
     * @todo: check all units belong to the user?
     * @test
     * @return void
     */
    public function it_lists_all_food_units_and_includes_calories_for_a_specific_food()
    {
        $this->markTestIncomplete();
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/foodUnits?includeCaloriesForSpecificFood=true&food_id=2');
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkFoodUnitKeysExist($content[0]);
        $food = $content[1];

        $this->assertEquals(6, $food['id']);
        $this->assertEquals('grams', $food['name']);
        $this->assertEquals('food', $food['for']);
        $this->assertNull($food['calories']);
        $this->assertEquals('5.00', $content[1]['calories']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_create_a_unit()
    {
        DB::beginTransaction();
        $this->logInUser();

        $unit = [
            'name' => 'koala'
        ];

        $response = $this->call('POST', '/api/foodUnits', $unit);
        $content = json_decode($response->getContent(), true);
     // dd($content);

        $this->checkFoodUnitKeysExist($content);

        $this->assertEquals('koala', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_a_unit()
    {
        DB::beginTransaction();
        $this->logInUser();

        $unit = Unit::forCurrentUser()->where('for', 'food')->first();

        $response = $this->call('PUT', '/api/foodUnits/'.$unit->id, [
            'name' => 'numbat'
        ]);
        $content = json_decode($response->getContent(), true);
        //dd($content);

        $this->checkFoodUnitKeysExist($content);

        $this->assertEquals('numbat', $content['name']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_delete_a_food_unit()
    {
        $this->logInUser();

        $name = 'echidna';

        $unit = new Unit([
            'name' => 'echidna',
            'for' => 'food'
        ]);
        $unit->user()->associate($this->user);
        $unit->save();

        $this->seeInDatabase('units', ['name' => 'echidna']);

        $response = $this->call('DELETE', '/api/foodUnits/'.$unit->id);
        $this->assertEquals(204, $response->getStatusCode());
        $this->missingFromDatabase('units', ['name' => 'echidna']);

        // @TODO Test the 404 for the other methods as well (show, update)
        $response = $this->call('DELETE', '/api/units/0');
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_cannot_delete_a_food_unit_that_is_in_use()
    {
        DB::beginTransaction();
        $this->logInUser();

        $unit = Unit::where('for', 'food')->first();

        $response = $this->call('DELETE', '/api/foodUnits/'.$unit->id);
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('error', $content);

        $this->assertEquals('Unit could not be deleted. It is in use.', $content['error']);
        $this->assertEquals(400, $response->getStatusCode());

        DB::rollBack();
    }
}
