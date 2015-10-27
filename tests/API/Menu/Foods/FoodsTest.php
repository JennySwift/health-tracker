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

        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);
        $this->assertArrayHasKey('path', $content[0]);
        $this->assertArrayHasKey('defaultCalories', $content[0]);
        $this->assertArrayHasKey('defaultUnit', $content[0]);

        $this->assertEquals(3, $content[0]['id']);
        $this->assertEquals('buckwheat', $content[0]['name']);
        $this->assertEquals('http://localhost/api/foods/3', $content[0]['path']);
        $this->assertEquals('5.00', $content[0]['defaultCalories']);

        $this->assertEquals([
            'id' => 3,
            'name' => 'small'
        ], $content[0]['defaultUnit']);

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
