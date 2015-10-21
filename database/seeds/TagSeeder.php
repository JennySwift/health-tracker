<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Tags\Tag;
use Faker\Factory as Faker;

class TagSeeder extends Seeder {

	public function run()
	{
		Tag::truncate();
        $users = User::all();

        foreach($users as $user) {
            Tag::create([
                'name' => 'main meal',
                'for' => 'recipe',
                'user_id' => $user->id
            ]);

            Tag::create([
                'name' => 'soup',
                'for' => 'recipe',
                'user_id' => $user->id
            ]);

            Tag::create([
                'name' => 'pushups',
                'for' => 'exercise',
                'user_id' => $user->id
            ]);

            Tag::create([
                'name' => 'pullups',
                'for' => 'exercise',
                'user_id' => $user->id
            ]);
        }
		

	}

}