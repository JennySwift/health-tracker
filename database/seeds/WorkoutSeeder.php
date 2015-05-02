<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Workout;
//done

class WorkoutSeeder extends Seeder {

	public function run()
	{
		Workout::truncate();
		
		Workout::create([
			'name' => 'day one',
			'user_id' => 1
		]);

		Workout::create([
			'name' => 'day two',
			'user_id' => 1
		]);
	}

}