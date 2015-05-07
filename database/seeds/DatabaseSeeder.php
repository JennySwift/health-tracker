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
		/**
		 * Note: Technically, you shouldn't have to run $this->command->info() since each seeder
		 * will show up in the console when you run `php artisan db:seed`
		 * You can of course keep them if you prefer it, no worries ;)
		 */

		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		Model::unguard();

		$this->call('UserSeeder');
		$this->command->info('user table seeded!');

		// foods
		$this->call('CaloriesSeeder');
		$this->command->info('calories table seeded!');

		$this->call('FoodEntrySeeder');
		$this->command->info('food_entries table seeded!');

		$this->call('FoodRecipeSeeder');
		$this->command->info('food_recipe table seeded!');

		$this->call('FoodSeeder');
		$this->command->info('foods table seeded!');

		$this->call('UnitSeeder');
		$this->command->info('units table seeded!');

		$this->call('RecipeMethodSeeder');
		$this->command->info('recipe_methods table seeded!');

		$this->call('RecipeSeeder');
		$this->command->info('recipes table seeded!');

		//exercises
		$this->call('ExerciseEntrySeeder');
		$this->command->info('exercise_entries table seeded!');

		$this->call('ExerciseSeeder');
		$this->command->info('exercises table seeded!');

		$this->call('ExerciseSeriesSeeder');
		$this->command->info('exercise_series table seeded!');

		// $this->call('SeriesWorkoutSeeder');
		// $this->command->info('series_workout table seeded!');

		$this->call('WorkoutSeeder');
		$this->command->info('workouts table seeded!');

		//weight
		$this->call('WeightSeeder');
		$this->command->info('weight table seeded!');

		//journal
		// $this->call('JournalSeeder');
		// $this->command->info('journal_entries table seeded!');

		//tags

		/**
		 * @VP:
		 * When I uncomment the following and try to seed my database, I get the error:
		 * [ReflectionException]           
  		 * Class TagSeeder does not exist
  		 * ?? I thought I'd done it the same as all my other seeders that exist.
		 */

		// $this->call('TagSeeder');
		// $this->command->info('tags table seeded!');

		// $this->call('TaggablesSeeder');
		// $this->command->info('taggables table seeded!');

		DB::statement('SET FOREIGN_KEY_CHECKS=1');

	}

}
