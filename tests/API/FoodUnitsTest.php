<?php

use App\Models\Units\Unit;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class FoodUnitsTest extends TestCase {

    use DatabaseTransactions;

    /**
     * It lists the foods for the user
     * @test
     * @return void
     */
    public function it_lists_all_food_units()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/foodUnits');
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);
        $this->assertArrayHasKey('user_id', $content[0]);
        $this->assertArrayHasKey('for', $content[0]);

        $this->assertEquals(6, $content[0]['id']);
        $this->assertEquals(1, $content[0]['user_id']);
        $this->assertEquals('grams', $content[0]['name']);
        $this->assertEquals('food', $content[0]['for']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_add_a_new_unit()
    {
        $this->logInUser();

        $unit = [
            'name' => 'kangaroo'
        ];

        $response = $this->call('POST', '/api/foodUnits', $unit);
        $content = json_decode($response->getContent(), true)['data'];

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('for', $content);

        $this->assertContains($unit['name'], $content);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_a_unit()
    {
        $this->logInUser();

        $unit = Unit::forCurrentUser()->where('for', 'food')->first();

        $response = $this->call('PUT', '/api/foodUnits/'.$unit->id, [
            'name' => 'numbat'
        ]);
        $content = json_decode($response->getContent(), true)['data'];

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('for', $content);

        $this->assertEquals('numbat', $content['name']);

        $this->assertEquals(200, $response->getStatusCode());
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
}
