<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

use App\Models\Units\Unit;

class FoodRecipeSeeder extends Seeder {

	public function run()
	{
		DB::table('food_recipe')->truncate();
		
		$faker = Faker::create();
		$unit_ids = Unit::where('for', 'food')->lists('id');

		DB::table('food_recipe')->insert([
			'recipe_id' => 1,
			'food_id' => 1,
			'unit_id' => $faker->randomElement($unit_ids),
			'quantity' => 3,
			'description' => '',
			'user_id' => 1
		]);

		DB::table('food_recipe')->insert([
			'recipe_id' => 1,
			'food_id' => 2,
			'unit_id' => $faker->randomElement($unit_ids),
			'quantity' => 3,
			'description' => '',
			'user_id' => 1
		]);

		DB::table('food_recipe')->insert([
			'recipe_id' => 1,
			'food_id' => 3,
			'unit_id' => $faker->randomElement($unit_ids),
			'quantity' => 3,
			'description' => '',
			'user_id' => 1
		]);
	}

}