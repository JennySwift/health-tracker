<?php

use App\Models\Menu\Recipe;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Todo: Write test for adding food, and method to recipe
 * Class RecipesTest
 */
class RecipesTest extends TestCase {

    use DatabaseTransactions;

    /**
     * It lists the recipes for the user
     * @test
     * @return void
     */
    public function it_lists_all_recipes()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/recipes');
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);
        $this->assertArrayHasKey('tags', $content[0]);

        $this->assertEquals(1, $content[0]['id']);
        $this->assertEquals('delicious recipe', $content[0]['name']);
        $this->assertEquals(1, $content[0]['tags']['data'][0]['id']);
        $this->assertEquals('main meal', $content[0]['tags']['data'][0]['name']);
        $this->assertCount(2, $content);
        $this->assertCount(2, $content[0]['tags']['data']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Todo: check for more things
     * @test
     */
    public function it_can_show_a_recipe()
    {
        $this->logInUser();

        $recipe = Recipe::forCurrentUser()->first();

        $response = $this->call('GET', '/api/recipes/' . $recipe->id);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('steps', $content);
        $this->assertArrayHasKey('tag_ids', $content);
        $this->assertArrayHasKey('ingredients', $content);
        $this->assertArrayHasKey('tags', $content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('delicious recipe', $content['name']);
        $this->assertCount(5, $content['steps']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    /**
     * @test
     * @return void
     */
    public function it_can_add_a_new_recipe()
    {
        $this->logInUser();

        $recipe = [
            'name' => 'kangaroo'
        ];

        $response = $this->call('POST', '/api/recipes', $recipe);
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);

        $this->assertEquals('kangaroo', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * Todo: check for other things
     * @test
     */
    public function it_finds_similar_names()
    {
        $this->logInUser();

        $data = [
            'check_for_similar_names' => true,
            'name' => 'super recipe',
            'ingredients' => [
                [
                    'description' => 'chopped',
                    'food' => 'rices',
                    'quantity' => 1,
                    'unit' => 'small'
                ],
                [
                    'food' => 'bananas',
                    'quantity' => 2,
                    'unit' => 'large'
                ]
            ],
            'steps' => ['Blend', 'Eat']
        ];

        $response = $this->call('POST', '/api/quickRecipes', $data);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('similar_names', $content);
        $this->assertArrayHasKey('foods', $content['similar_names']);
//        $this->assertArrayHasKey('foods', $content['similar_names']['foods']);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_insert_a_quick_recipe()
    {
        $this->logInUser();

        $data = [
            'check_for_similar_names' => true,
            'name' => 'super recipe',
            'ingredients' => [
                [
                    'description' => 'chopped',
                    'food' => 'apple',
                    'quantity' => 1,
                    'unit' => 'small'
                ],
                [
//                    'description' => 'chopped',
                    'food' => 'bananas',
                    'quantity' => 2,
                    'unit' => 'large'
                ]
            ],
            'steps' => ['Blend', 'Eat']
        ];

        $response = $this->call('POST', '/api/quickRecipes', $data);
//        dd($response);
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

        //Check the response is correct
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('tags', $content);

        $this->assertEquals('super recipe', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        //Check the recipe was inserted correctly
        $response = $this->call('GET', '/api/recipes/' . $content['id']);
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('steps', $content);
        $this->assertArrayHasKey('tag_ids', $content);
        $this->assertArrayHasKey('ingredients', $content);
        $this->assertArrayHasKey('tags', $content);

        $this->assertEquals('super recipe', $content['name']);
        $this->assertEquals('Blend', $content['steps'][0]['text']);
        $this->assertEquals(1, $content['steps'][0]['step']);
        $this->assertEquals('Eat', $content['steps'][1]['text']);
        $this->assertEquals(2, $content['steps'][1]['step']);

//        $this->assertEquals(7, $content['ingredients'][0]['food_id']);
        $this->assertEquals('apple', $content['ingredients'][0]['name']);
        $this->assertEquals('small', $content['ingredients'][0]['unit_name']);
        $this->assertEquals(3, $content['ingredients'][0]['unit_id']);
        $this->assertEquals('1.00', $content['ingredients'][0]['quantity']);
        $this->assertEquals('chopped', $content['ingredients'][0]['description']);
        $this->assertEquals([], $content['ingredients'][0]['units']);

//        $this->assertEquals(8, $content['ingredients'][0]['food_id']);
        $this->assertEquals('bananas', $content['ingredients'][1]['name']);
        $this->assertEquals('large', $content['ingredients'][1]['unit_name']);
        $this->assertEquals(5, $content['ingredients'][1]['unit_id']);
        $this->assertEquals('2.00', $content['ingredients'][1]['quantity']);
        $this->assertEquals('', $content['ingredients'][1]['description']);
        $this->assertEquals([], $content['ingredients'][1]['units']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
//    public function it_can_update_a_recipe()
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
    public function it_can_delete_a_recipe()
    {
        $this->logInUser();

        $recipe = new Recipe([
            'name' => 'echidna'
        ]);

        $recipe->user()->associate($this->user);
        $recipe->save();

        $this->seeInDatabase('recipes', ['name' => 'echidna']);

        $response = $this->call('DELETE', '/api/recipes/'.$recipe->id);
        $this->assertEquals(204, $response->getStatusCode());
        $this->missingFromDatabase('recipes', ['name' => 'echidna']);

        $response = $this->call('DELETE', '/api/recipes/' . $recipe->id);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
