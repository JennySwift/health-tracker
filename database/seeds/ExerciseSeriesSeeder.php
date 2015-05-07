<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Series;

class ExerciseSeriesSeeder extends Seeder {

	public function run()
	{
		Series::truncate();

		Series::create([
			'name' => 'pushup',
			'user_id' => 1
		]);

		Series::create([
			'name' => 'pullup',
			'user_id' => 1
		]);

		Series::create([
			'name' => 'squat',
			'user_id' => 1
		]);
	}

}