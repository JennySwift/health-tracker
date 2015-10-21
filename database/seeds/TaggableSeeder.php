<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class TaggableSeeder extends Seeder {

	public function run()
	{
		DB::table('taggables')->truncate();
        $users = User::all();

        //Todo: These tag ids and taggable ids don't belong to the appropriate users
        foreach($users as $user) {
            DB::table('taggables')->insert([
                'tag_id' => 3,
                'taggable_id' => 1,
                'taggable_type' => 'exercise'
            ]);

            DB::table('taggables')->insert([
                'tag_id' => 4,
                'taggable_id' => 1,
                'taggable_type' => 'exercise'
            ]);

            DB::table('taggables')->insert([
                'tag_id' => 1,
                'taggable_id' => 1,
                'taggable_type' => 'recipe'
            ]);

            DB::table('taggables')->insert([
                'tag_id' => 1,
                'taggable_id' => 2,
                'taggable_type' => 'recipe'
            ]);

            DB::table('taggables')->insert([
                'tag_id' => 2,
                'taggable_id' => 2,
                'taggable_type' => 'recipe'
            ]);
        }


	}

}