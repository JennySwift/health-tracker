<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\FoodRecipe;
use Faker\Factory as Faker;

class FoodRecipeSeeder extends Seeder {

	public function run()
	{
		FoodRecipe::truncate();
		
		$faker = Faker::create();

		FoodRecipe::create([
			'recipe_id' => 1,
			'food_id' => 1,
			'unit_id' => 1,
			'quantity' => 3,
			'description' => '',
			'user_id' => 1
		]);

		FoodRecipe::create([
			'recipe_id' => 1,
			'food_id' => 2,
			'unit_id' => 1,
			'quantity' => 3,
			'description' => '',
			'user_id' => 1
		]);

		FoodRecipe::create([
			'recipe_id' => 1,
			'food_id' => 3,
			'unit_id' => 1,
			'quantity' => 3,
			'description' => '',
			'user_id' => 1
		]);
	}

}