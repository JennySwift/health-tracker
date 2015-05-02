<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Series;
//done
class ExerciseSeriesSeeder extends Seeder {

	public function run()
	{
		ExerciseSeries::truncate();

		ExerciseSeries::create([
			'name' => 'pushup',
			'user_id' => 1
		]);

		ExerciseSeries::create([
			'name' => 'pullup',
			'user_id' => 1
		]);

		ExerciseSeries::create([
			'name' => 'squat',
			'user_id' => 1
		]);
	}

}