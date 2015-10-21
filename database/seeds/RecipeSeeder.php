<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\Recipe;

class RecipeSeeder extends Seeder {

	public function run()
	{
		Recipe::truncate();
        $users = User::all();

        foreach($users as $user) {
            DB::table('recipes')->insert([
                'name' => 'delicious recipe',
                'user_id' => $user->id
            ]);

            DB::table('recipes')->insert([
                'name' => 'fruit salad',
                'user_id' => $user->id
            ]);
        }

	}

}