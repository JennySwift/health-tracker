<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Exercise;
use App\Models\Units\Unit;
use App\Models\Exercises\Series;
use Faker\Factory as Faker;

class ExerciseSeeder extends Seeder {

	public function run()
	{
		Exercise::truncate();

		$faker = Faker::create();

		$pushup_series = ['kneeling pushups', 'pushups', 'one-arm pushups'];
		$squat_series = ['assisted squats', 'squats', 'one-legged-squats'];

		$exercise_unit_ids = Unit::where('for', 'exercise')->lists('id');
		$series_ids = Series::lists('id');

		$index = 0;
		foreach ($pushup_series as $exercise) {
			$index++;
			DB::table('exercises')->insert([
				'name' => $exercise,
				'default_exercise_unit_id' => $faker->randomElement($exercise_unit_ids),
				'description' => '',
				'default_quantity' => $faker->numberBetween($min = 5, $max = 20),
				'step_number' => $index,
				'series_id' => 1,
				'user_id' => 1
			]);
		}

		$index = 0;
		foreach ($squat_series as $exercise) {
			$index++;
			DB::table('exercises')->insert([
				'name' => $exercise,
				'default_exercise_unit_id' => $faker->randomElement($exercise_unit_ids),
				'description' => '',
				'default_quantity' => $faker->numberBetween($min = 5, $max = 20),
				'step_number' => $index,
				'series_id' => 3,
				'user_id' => 1
			]);
		}
	}

}