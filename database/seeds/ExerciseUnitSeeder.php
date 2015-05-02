<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Unit;
//done
class ExerciseUnitSeeder extends Seeder {

	public function run()
	{
		ExerciseUnit::truncate();
		
		ExerciseUnit::create([
			'name' => 'reps',
			'user_id' => 1
		]);

		ExerciseUnit::create([
			'name' => 'minutes',
			'user_id' => 1
		]);
	}

}