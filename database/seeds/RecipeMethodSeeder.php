<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\RecipeMethod;
use App\Models\Foods\Recipe;
use Faker\Factory as Faker;

class RecipeMethodSeeder extends Seeder {

	public function run()
	{
		RecipeMethod::truncate();
		
		$faker = Faker::create();

		$recipe_ids = Recipe::lists('id');

		/**
		 * @VP:
		 * Nothing is being entered into this table. I suppose $recipe_ids is null, but I don't know why.
		 */
		
		foreach ($recipe_ids as $recipe_id) {
			// $counter = 0;
			foreach (range(1, 5) as $index) {
				// $counter++;
				RecipeMethod::create([
					'recipe_id' => $recipe_id,
					'step' => $index,
					'text' => $faker->sentence,
					'user_id' => 1
				]);
			}	
		}	
	}

}