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

		// foods
		$this->call('CaloriesSeeder');

		$this->call('FoodEntrySeeder');

		$this->call('FoodRecipeSeeder');

		$this->call('FoodSeeder');

		$this->call('UnitSeeder');

		$this->call('RecipeMethodSeeder');

		$this->call('RecipeSeeder');

		//exercises
		$this->call('ExerciseEntrySeeder');

		$this->call('ExerciseSeeder');

		$this->call('ExerciseSeriesSeeder');

		// $this->call('SeriesWorkoutSeeder');

		$this->call('WorkoutSeeder');

		//weight
		$this->call('WeightSeeder');

		//journal
		$this->call('JournalSeeder');

		//tags

		$this->call('TagSeeder');

		$this->call('TaggableSeeder');

		DB::statement('SET FOREIGN_KEY_CHECKS=1');

	}

}
