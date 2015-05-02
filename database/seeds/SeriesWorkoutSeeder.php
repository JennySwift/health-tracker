<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;

class SeriesWorkoutSeeder extends Seeder {

	public function run()
	{
		SeriesWorkout::truncate();

		/**
		 * workout_id, series_id, user_id
		 */
		
		$faker = Faker::create();

		foreach (range(1, 5) as $index) {
			SeriesWorkout::create([
				'name' => $faker->word,
				'email' => $faker->email,
				'password' => 'secret'
			]);
		}
	}

}