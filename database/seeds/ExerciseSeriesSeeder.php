<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Series;

class ExerciseSeriesSeeder extends Seeder {

	public function run()
	{
		Series::truncate();

		DB::table('exercise_series')->insert([
			'name' => 'pushup',
			'user_id' => 1
		]);

		DB::table('exercise_series')->insert([
			'name' => 'pullup',
			'user_id' => 1
		]);

		DB::table('exercise_series')->insert([
			'name' => 'squat',
			'user_id' => 1
		]);
	}

}