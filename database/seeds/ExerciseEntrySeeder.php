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

			//Create the entries for the same exercise but with different units
			//for today, so that I can test out the getSpecificExerciseEntries
			//for a day where the exercise is entered with different units
			if ($date === Carbon::today()->format('Y-m-d')) {
				// dd('if' . $date);
				foreach (range(0, 2) as $index) {
					ExerciseEntry::create([
						'date' => $date,
						'exercise_id' => 1,
						'quantity' => $faker->numberBetween($min = 4, $max = 30),
						'exercise_unit_id' => 1,
						'user_id' => 1
					]);
				}
				foreach (range(0, 1) as $index) {
					ExerciseEntry::create([
						'date' => $date,
						'exercise_id' => 1,
						'quantity' => $faker->numberBetween($min = 4, $max = 30),
						'exercise_unit_id' => 2,
						'user_id' => 1
					]);
				}
			}

			else {
				// dd('else' . $date);
				foreach (range(0, 2) as $index) {
					ExerciseEntry::create([
						'date' => $date,
						'exercise_id' => $exercise_id,
						'quantity' => $faker->numberBetween($min = 4, $max = 30),
						'exercise_unit_id' => $unit_id,
						'user_id' => 1
					]);
				}
			}
		}		
	}
}