<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\Recipe;

class RecipeSeeder extends Seeder {

	public function run()
	{
		Recipe::truncate();
		
		DB::table('recipes')->insert([
			'name' => 'delicious recipe',
			'user_id' => 1
		]);

		DB::table('recipes')->insert([
			'name' => 'fruit salad',
			'user_id' => 1
		]);
	}

}