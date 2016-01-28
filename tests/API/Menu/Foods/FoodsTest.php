<?php

use App\Models\Menu\Food;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Todo: Write test for adding unit to food
 * Class FoodsTest
 */
class FoodsTest extends TestCase {

    use DatabaseTransactions;

    /**
     * It lists the foods for the user
     * @test
     * @return void
     */
    public function it_lists_all_foods()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/foods');
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkFoodKeysExist($content[0]);

        $this->assertEquals('apple', $content[0]['name']);
        $this->assertEquals('5.00', $content[0]['defaultCalories']);

        $this->assertEquals([
            'id' => 3,
            'name' => 'small',
            'for' => 'food'
        ], $content[0]['defaultUnit']['data']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_autocomplete_the_foods()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/foods?typing=p');
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

        $this->checkFoodKeysExist($content[0]);
        $this->assertArrayHasKey('units', $content[0]);

        //Check the units are correct for the second food
        $this->assertCount(2, $content[1]['units']['data']);
        $this->assertEquals('grams', $content[1]['units']['data'][0]['name']);
        $this->assertEquals('medium', $content[1]['units']['data'][1]['name']);
        $this->assertEquals('medium', $content[1]['defaultUnit']['data']['name']);

        foreach ($content as $food) {
            $this->assertContains('p', $food['name']);
        }

        //Check the default unit is the same as the unit in the units array,
        //so the select box works
        $this->assertEquals($content[1]['defaultUnit']['data'], $content[1]['units']['data'][1]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_add_a_new_food()
    {
        $this->logInUser();

        $food = [
            'name' => 'kangaroo'
        ];

        $response = $this->call('POST', '/api/foods', $food);
        $content = json_decode($response->getContent(), true)['data'];

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('path', $content);
        $this->assertArrayHasKey('defaultCalories', $content);
//        $this->assertArrayHasKey('defaultUnit', $content);

        $this->assertEquals('kangaroo', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
//    public function it_can_update_a_food()
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
    public function it_can_delete_a_food()
    {
        $this->logInUser();

        $food = new Food([
            'name' => 'echidna'
        ]);

        $food->user()->associate($this->user);
        $food->save();

        $this->seeInDatabase('foods', ['name' => 'echidna']);

        $response = $this->call('DELETE', '/api/foods/'.$food->id);
        $this->assertEquals(204, $response->getStatusCode());
        $this->missingFromDatabase('foods', ['name' => 'echidna']);

        $response = $this->call('DELETE', '/api/foods/' . $food->id);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
