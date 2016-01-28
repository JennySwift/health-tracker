<?php

use App\Models\Menu\Food;
use App\Models\Menu\Recipe;
use App\Models\Units\Unit;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class RecipeUpdateTest
 */
class RecipeUpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_update_a_recipe()
    {
        DB::beginTransaction();
        $this->logInUser();

        $recipe = Recipe::forCurrentUser()->first();

        $response = $this->call('PUT', '/api/recipes/'.$recipe->id, [
            'name' => 'numbat',
            'steps' => ['one', 'two', 'three'],
            'tag_ids' => [1,3],
            'ingredients' => [
                ['food_id' => 2, 'unit_id' => 3],
                ['food_id' => 4, 'unit_id' => 4]
            ]
        ]);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkRecipeKeysExist($content);

        //Todo: check other attributes are correct
        $this->assertEquals('numbat', $content['name']);
        $this->assertEquals([1,3], $content['tag_ids']);
        $this->checkIngredientKeysExist($content['ingredients'][0]);
        $this->assertEquals(2, $content['ingredients'][0]['food_id']);
        $this->assertEquals(4, $content['ingredients'][1]['food_id']);
        $this->assertCount(2, $content['ingredients']);

        //Check the steps are correct
        $this->assertEquals(1, $content['steps'][0]['step']);
        $this->assertEquals('one', $content['steps'][0]['text']);
        $this->assertEquals(2, $content['steps'][1]['step']);
        $this->assertEquals('two', $content['steps'][1]['text']);
        $this->assertEquals(3, $content['steps'][2]['step']);
        $this->assertEquals('three', $content['steps'][2]['text']);
        $this->assertCount(3, $content['steps']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     */
    public function it_can_remove_an_ingredient_from_a_recipe()
    {
        DB::beginTransaction();
        $this->logInUser();

        $recipe = Recipe::forCurrentUser()->first();

        $ingredient = DB::table('food_recipe')
            ->where('recipe_id', $recipe->id)
            ->first();

        $this->seeInDatabase('food_recipe', [
            'food_id' => $ingredient->food_id,
            'unit_id' => $ingredient->unit_id
        ]);

        $foodCount = count($recipe->foods);

        $response = $this->call('PUT', '/api/recipes/'.$recipe->id, [
            'removeIngredient' => true,
            'food_id' => $ingredient->food_id,
            'unit_id' => $ingredient->unit_id
        ]);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->missingFromDatabase('food_recipe', [
            'food_id' => $ingredient->food_id,
            'unit_id' => $ingredient->unit_id
        ]);

        $this->assertCount($foodCount - 1, $recipe->foods()->get());

        $this->checkRecipeKeysExist($content);

        $this->checkIngredientKeysExist($content['ingredients'][0]);
        $this->assertCount($foodCount -1, $content['ingredients']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     */
    public function it_can_add_an_ingredient_to_a_recipe()
    {
        DB::beginTransaction();
        $this->logInUser();

        $recipe = Recipe::forCurrentUser()->first();

        $food = Food::forCurrentUser()->offset(2)->first();
        $unit = Unit::forCurrentUser()->where('for', 'food')->offset(2)->first();

        $data = [
            'addIngredient' => true,
            'food_id' => $food->id,
            'unit_id' => $unit->id,
            'quantity' => 9,
            'description' => 'blah blah'
        ];


        $foodCount = count($recipe->foods);

        $response = $this->call('PUT', '/api/recipes/'.$recipe->id, $data);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->seeInDatabase('food_recipe', [
            'food_id' => $food->id,
            'unit_id' => $unit->id,
            'quantity' => 9,
            'description' => 'blah blah'
        ]);

        $this->assertCount($foodCount + 1, $recipe->foods()->get());

        $this->checkRecipeKeysExist($content);

        $this->checkIngredientKeysExist($content['ingredients'][0]);
        $this->assertCount($foodCount + 1, $content['ingredients']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }


}