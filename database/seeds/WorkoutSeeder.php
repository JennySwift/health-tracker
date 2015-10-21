<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Workout;

class WorkoutSeeder extends Seeder {

	public function run()
	{
		Workout::truncate();
        $users = User::all();

        foreach($users as $user) {
            DB::table('workouts')->insert([
                'name' => 'day one',
                'user_id' => $user->id
            ]);

            DB::table('workouts')->insert([
                'name' => 'day two',
                'user_id' => $user->id
            ]);
        }
		

	}

}