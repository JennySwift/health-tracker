<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Workout;

class WorkoutSeeder extends Seeder {

	public function run()
	{
		Workout::truncate();
		
		DB::table('workouts')->insert([
			'name' => 'day one',
			'user_id' => 1
		]);

		DB::table('workouts')->insert([
			'name' => 'day two',
			'user_id' => 1
		]);
	}

}