<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Debugbar;

use App\Exercise;
use App\Exercise_series;
use App\Exercise_entries;
use App\Exercise_tags;
use App\Exercise_units;

class SelectController extends Controller {
	//
	public function pageLoad () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$response = array(
			"foods" => getFoods(),
			// "recipes" => getRecipes(),
			"recipes" => filterRecipes('', []),
			"food_units" => getFoodUnits(),
			"foods_with_units" => getAllFoodsWithUnits(),
			"weight" => getWeight($date),
			"exercise_units" => Exercise_units::getExerciseUnits(),
			"exercises" => Exercise::getExercises(),
			"exercise_series" => Exercise_series::getExerciseSeries(),
			"food_entries" => getFoodEntries($date),
			"calories_for_the_day" => number_format(getCaloriesForTimePeriod($date, "day"), 2),
			"calories_for_the_week" => number_format(getCaloriesForTimePeriod($date, "week"), 2),
			"exercise_entries" => Exercise_entries::getExerciseEntries($date),
			"journal_entry" => getJournalEntry($date),
			"exercise_tags" => Exercise_tags::getExerciseTags(),
			"workouts" => getWorkouts(),
			"recipe_tags" => getRecipeTags()
		);
		return $response;
	}

	//Which controller? Selects rows from both foods and recipes table.
	public function autocompleteMenu () {
		include(app_path() . '/inc/functions.php');
		$typing = json_decode(file_get_contents('php://input'), true)["typing"];		
		return autocompleteMenu($typing);
	}

	//Which controller?
	public function getEntries () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$response = array(
			"weight" => getWeight($date),
			"exercise_entries" => getExerciseEntries($date),
			"journal_entry" => getJournalEntry($date),

			"food_entries" => getFoodEntries($date),
			"calories_for_the_day" => number_format(getCaloriesForTimePeriod($date, "day"), 2),
			"calories_for_the_week" => number_format(getCaloriesForTimePeriod($date, "week"), 2)
		);
		return $response;
	}
}
