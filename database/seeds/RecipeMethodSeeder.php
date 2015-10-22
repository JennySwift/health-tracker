<?php

use App\Models\Menu\Recipe;
use App\Models\Menu\RecipeMethod;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class RecipeMethodSeeder extends Seeder
{

    public function run()
    {
        RecipeMethod::truncate();

        $faker = Faker::create();
        $users = User::all();

        foreach ($users as $user) {
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