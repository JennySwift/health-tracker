<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exercises\Workouts\Series as SeriesWorkout;
use App\Models\Exercises\Workout;
use App\Models\Exercises\Series as ExerciseSeries;
use Faker\Factory as Faker;

class SeriesWorkoutSeeder extends Seeder {

	public function run()
	{
		SeriesWorkout::truncate();
		
		$faker = Faker::create();

		$workout_ids = Workout::lists('id');
		$series_ids = ExerciseSeries::lists('id');
		

		foreach (range(1, 5) as $index) {
			/**
			 * @VP: You said most of the time I don't need a model for a pivot table. So how would I populate the series_workout table in this example if I didn't have the SeriesWorkout model?
			 */

			SeriesWorkout::create([
				/**
				 * @VP:
				 * When I run the seeder I get an error saying, "Column 'workout_id' cannot be null."
				 * Firstly, why is it trying to enter 'null' into my workout_id column? (Why isn't my code working here?)
				 * Secondly, how do I debug code in my seeder files, i.e., how do I display the value of $workout_ids somewhere so I can debug?
				 * In case you need to run the seeder yourself, just uncomment line 53 in DatabaseSeeder.php. I commented it out because it was causing my seeder to error.
				 */

				'workout_id' => $faker->randomElement($workout_ids),
				'series_id' => $faker->randomElement($series_ids),
				'user_id' => 1
			]);
		}
	}

}