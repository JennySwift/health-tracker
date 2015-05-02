<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Exercise;
use Faker\Factory as Faker;

class ExerciseSeeder extends Seeder {

	public function run()
	{
		Exercise::truncate();

		$faker = Faker::create();

		Exercise::create([
			'name' => 'kneeling pushups',
			'default_exercise_unit_id' => $something,
			'description' => '',
			'default_quantity' => $default_quantity,
			'step_number' => $step_number,
			'series_id' => $series_id,
			'user_id' => 1
		]);

		Exercise::create([
			'name' => 'pushups',
			'default_exercise_unit_id' => 1,
			'description' => '',
			'default_quantity' => $default_quantity,
			'step_number' => $step_number,
			'series_id' => $series_id,
			'user_id' => 1
		]);

		Exercise::create([
			'name' => 'one arm pushups',
			'default_exercise_unit_id' => $something,
			'description' => '',
			'default_quantity' => $default_quantity,
			'step_number' => $step_number,
			'series_id' => $series_id,
			'user_id' => 1
		]);
	}

}