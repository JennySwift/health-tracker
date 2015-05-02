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
		$this->command->info('user table seeded!');

		//foods
		$this->call('CaloriesSeeder');
		$this->command->info('calories table seeded!');

		$this->call('FoodEntrySeeder');
		$this->command->info('food_entries table seeded!');

		$this->call('FoodRecipeSeeder');
		$this->command->info('food_recipe table seeded!');

		$this->call('FoodSeeder');
		$this->command->info('foods table seeded!');

		$this->call('FoodUnitSeeder');
		$this->command->info('food_units table seeded!');

		$this->call('RecipeMethodSeeder');
		$this->command->info('recipe_methods table seeded!');

		$this->call('RecipeSeeder');
		$this->command->info('recipes table seeded!');

		//exercises
		$this->call('ExerciseEntrySeeder');
		$this->command->info('exercise_entries table seeded!');

		// $this->call('ExerciseSeeder');
		// $this->command->info('exercises table seeded!');

		// $this->call('ExerciseSeriesSeeder');
		// $this->command->info('exercise_series table seeded!');

		// $this->call('ExerciseUnitSeeder');
		// $this->command->info('exercise_units table seeded!');

		// $this->call('SeriesWorkoutSeeder');
		// $this->command->info('series_workout table seeded!');

		// $this->call('WorkoutSeeder');
		// $this->command->info('workouts table seeded!');

		//weight
		// $this->call('WeightSeeder');
		// $this->command->info('weight table seeded!');

		//journal
		// $this->call('JournalSeeder');
		// $this->command->info('journal_entries table seeded!');

		DB::statement('SET FOREIGN_KEY_CHECKS=1');

	}

}
