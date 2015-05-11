<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class FoodRecipeSeeder extends Seeder {

	public function run()
	{
		DB::table('food_recipe')->truncate();
		
		$faker = Faker::create();

		DB::table('food_recipe')->insert([
			'recipe_id' => 1,
			'food_id' => 1,
			'unit_id' => 1,
			'quantity' => 3,
			'description' => '',
			'user_id' => 1
		]);

		DB::table('food_recipe')->insert([
			'recipe_id' => 1,
			'food_id' => 2,
			'unit_id' => 1,
			'quantity' => 3,
			'description' => '',
			'user_id' => 1
		]);

		DB::table('food_recipe')->insert([
			'recipe_id' => 1,
			'food_id' => 3,
			'unit_id' => 1,
			'quantity' => 3,
			'description' => '',
			'user_id' => 1
		]);
	}

}