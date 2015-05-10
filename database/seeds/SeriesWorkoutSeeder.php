<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exercises\Workout;
use App\Models\Exercises\Series as ExerciseSeries;
use Faker\Factory as Faker;

class SeriesWorkoutSeeder extends Seeder {

	public function run()
	{
		DB::table('series_workout')->truncate();
		
		$faker = Faker::create();

		$workout_ids = Workout::lists('id');
		$series_ids = ExerciseSeries::lists('id');

		foreach (range(1, 5) as $index) {
			DB::table('series_workout')->insert([
				'workout_id' => $faker->randomElement($workout_ids),
				'series_id' => $faker->randomElement($series_ids),
				'user_id' => 1
			]);
		}
	}

}