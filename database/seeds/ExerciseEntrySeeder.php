<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Entry as ExerciseEntry;
use App\Models\Exercises\Exercise;
use App\Models\Units\Unit;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ExerciseEntrySeeder extends Seeder {

	public function run()
	{
		// DB::statement('SET FOREIGN_KEY_CHECKS=0');

		ExerciseEntry::truncate();
		
		$faker = Faker::create();

		$exercise_ids = Exercise::lists('id');
		$unit_ids = Unit::where('for', 'exercise')->lists('id');

		/**
		 * Create a few exercise entries for each of the last 50 days
		 */

		foreach (range(0, 49) as $index) {
			$today = Carbon::today();
			$date = $today->subDays($index)->format('Y-m-d');
			$exercise_id = $faker->randomElement($exercise_ids);
			$unit_id = $faker->randomElement($unit_ids);
			$user_id = 1;

			//Create the entries for the same exercise but with different units
			//for today, so that I can test out the getSpecificExerciseEntries
			//for a day where the exercise is entered with different units
			if ($date === Carbon::today()->format('Y-m-d')) {
				// dd('if' . $date);
				foreach (range(0, 2) as $index) {
					DB::table('exercise_entries')->insert([
						'date' => $date,
						'exercise_id' => 1,
						'quantity' => $faker->numberBetween($min = 4, $max = 30),
						'exercise_unit_id' => 1,
						'user_id' => $user_id
					]);
				}
				foreach (range(0, 1) as $index) {
					DB::table('exercise_entries')->insert([
						'date' => $date,
						'exercise_id' => 1,
						'quantity' => $faker->numberBetween($min = 4, $max = 30),
						'exercise_unit_id' => 2,
						'user_id' => 1
					]);
				}
			}

			else {
				/**
				 * Objective: Create a few entries for each of a few different exercises (no duplicates).
				 * Ideally, a random number of different exercises.
				 */
				
				$number = $faker->numberBetween($min = 1, $max = 6);
				$random_exercise_ids = $faker->randomElements($exercise_ids, $count = $number);

				//Insert a few sets for each $random_exercise_id
				foreach ($random_exercise_ids as $random_exercise_id) {
					//Reset the unit id for each different exercise
					$random_unit_id = $faker->randomElement($unit_ids);
					//Set the number of sets that will be done for the exercise
					$range_limit = $faker->numberBetween($min = 1, $max = 7);
					//Insert the same exercise with the same unit a few times so we have exercise sets.
					foreach (range(0, $range_limit) as $index) {
						DB::table('exercise_entries')->insert([
							'date' => $date,
							'exercise_id' => $random_exercise_id,
							'quantity' => $faker->numberBetween($min = 4, $max = 30),
							'exercise_unit_id' => $random_unit_id,
							'user_id' => 1
						]);
					}
				}	
			}
		}
		
		// DB::statement('SET FOREIGN_KEY_CHECKS=1');		
	}
}