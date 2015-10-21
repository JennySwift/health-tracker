<?php

use App\User;
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
        $users = User::all();

        foreach($users as $user) {
            $recipe_ids = Recipe::where('user_id', $user->id)->lists('id');

            foreach ($recipe_ids as $recipe_id) {
                // $counter = 0;
                foreach (range(1, 5) as $index) {
                    // $counter++;
                    DB::table('recipe_methods')->insert([
                        'recipe_id' => $recipe_id,
                        'step' => $index,
                        'text' => $faker->sentence,
                        'user_id' => $user->id
                    ]);
                }
            }
        }


	}

}