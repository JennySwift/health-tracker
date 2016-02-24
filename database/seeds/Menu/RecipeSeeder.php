<?php

use App\Models\Menu\Food;
use App\Models\Menu\Recipe;
use App\Models\Tags\Tag;
use App\Models\Units\Unit;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

/**
 * Class RecipeSeeder
 */
class RecipeSeeder extends Seeder
{
    /**
     * @var
     */
    private $user;

    /**
     *
     */
    public function run()
    {
        Recipe::truncate();
        DB::table('food_recipe')->truncate();
        DB::table('taggables')->truncate();
        $this->user = User::first();
        $this->faker = Faker::create();

        $this->createRecipes();
    }

    /**
     *
     */
    private function createRecipes()
    {
        foreach (Config::get('recipes') as $tempRecipe) {
            $recipe = new Recipe([
                'name' => $tempRecipe['name'],
            ]);

            $recipe->user()->associate($this->user);

            $recipe->save();

            foreach ($tempRecipe['ingredients'] as $ingredient) {
                $food = Food::where('user_id', $this->user->id)->where('name', $ingredient['food'])->first();
                $recipe->foods()->attach([$food->id => [
                    'unit_id' => Unit::where('user_id', $this->user->id)->where('name', $ingredient['unit'])->first()->id,
                    'quantity' => $ingredient['quantity'],
                    'description' => $ingredient['description'],
                ]]);
            }

            foreach ($tempRecipe['tags'] as $tempTag) {
                $tag = Tag::where('user_id', $this->user->id)->where('name', $tempTag)->first();

                $recipe->tags()->attach([$tag->id => [
                    'taggable_type' => 'recipe'
                ]]);
            }

            $recipe->save();
        }
    }
}