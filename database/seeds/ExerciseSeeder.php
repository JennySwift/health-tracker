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

		$exercise_unit_ids = Unit::where('for', 'exercise')->lists('id');
		$series_ids = Series::lists('id');

		Exercise::create([
			'name' => 'kneeling pushups',
			'default_exercise_unit_id' => $faker->randomElement($exercise_unit_ids),
			'description' => '',
			'default_quantity' => $faker->numberBetween($min = 5, $max = 20),
			'step_number' => $faker->numberBetween($min = 1, $max = 10),

			/**
			 * @VP:
			 * 'Null' is being entered into the 'series_id' column here. Why?
			 * 
			 * Simply because you are running this seeder before seeding the series in 
			 * your DatabaseSeeder.php file
			 */

			'series_id' => $faker->randomElement($series_ids),
			'user_id' => 1
		]);

		Exercise::create([
			'name' => 'pushups',
			'default_exercise_unit_id' => $faker->randomElement($exercise_unit_ids),
			'description' => '',
			'default_quantity' => $faker->numberBetween($min = 5, $max = 20),
			'step_number' => $faker->numberBetween($min = 1, $max = 10),
			'series_id' => $faker->randomElement($series_ids),
			'user_id' => 1
		]);

		Exercise::create([
			'name' => 'one arm pushups',
			'default_exercise_unit_id' => $faker->randomElement($exercise_unit_ids),
			'description' => '',
			'default_quantity' => $faker->numberBetween($min = 5, $max = 20),
			'step_number' => $faker->numberBetween($min = 1, $max = 10),
			'series_id' => $faker->randomElement($series_ids),
			'user_id' => 1
		]);
	}

}