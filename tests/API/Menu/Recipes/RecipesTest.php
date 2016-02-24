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

//        $this->checkRecipeKeysExist($content[0]);

        $this->assertEquals(1, $content[0]['id']);
        $this->assertEquals('fruit salad', $content[0]['name']);
        $this->assertArrayHasKey('tags', $content[0]);
        //Todo: the tags aren't consistent for the recipes in the seeder
//        $this->assertEquals(4, $content[0]['tags']['data'][0]['id']);
//        $this->assertEquals('dessert', $content[0]['tags']['data'][0]['name']);
        $this->assertCount(2, $content);
        $this->assertCount(3, $content[0]['tags']['data']);

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

        $this->checkRecipeKeysExist($content);
        $this->checkFoodUnitKeysExist($content['ingredients']['data'][0]['food']['data']['units']['data'][0]);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('fruit salad', $content['name']);
        $this->assertCount(5, $content['steps']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

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
