<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		Model::unguard();

		$this->call('UserSeeder');

		$this->call('UnitSeeder');

		// foods

		$this->call('FoodSeeder');

        $this->call('RecipeSeeder');

        $this->call('FoodEntrySeeder');

        $this->call('FoodRecipeSeeder');

		$this->call('RecipeMethodSeeder');

		//exercises
		$this->call('ExerciseSeriesSeeder');

		$this->call('ExerciseSeeder');

		$this->call('ExerciseEntrySeeder');

		$this->call('WorkoutSeeder');

		//weight
		$this->call('WeightSeeder');

		//journal
		$this->call('JournalSeeder');

		//tags

		$this->call('TagSeeder');

		$this->call('TaggableSeeder');

		$this->call('SleepSeeder');

		DB::statement('SET FOREIGN_KEY_CHECKS=1');

	}

}
