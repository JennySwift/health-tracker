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

		/**
		 * Objective:
		 * Make a random number of exercise series belong to each workout.
		 * No duplicate series for a workout. (Currently there are duplicates.)
		 */

		$workout_ids = Workout::lists('id');
		$series_ids = ExerciseSeries::lists('id');

		foreach ($workout_ids as $workout_id) {
			foreach (range(1, 3) as $index) {
				DB::table('series_workout')->insert([
					'workout_id' => $workout_id,
					'series_id' => $faker->randomElement($series_ids),
				]);
			}
		}
	}

}