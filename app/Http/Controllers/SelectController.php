<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Debugbar;

class SelectController extends Controller {
	//
	public function pageLoad () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$response = array(
			"foods" => getFoods(),
			"recipes" => getRecipes(),
			"food_units" => getFoodUnits(),
			"exercise_units" => getExerciseUnits(),
			"foods_with_units" => getAllFoodsWithUnits(),
			"weight" => getWeight($date),
			"exercise_units" => getExerciseUnits(),
			"exercises" => getExercises(),
			"exercise_series" => getExerciseSeries(),
			"food_entries" => getFoodEntries($date),
			"exercise_entries" => getExerciseEntries($date),
			"journal_entry" => getJournalEntry($date),
			"exercise_tags" => getExerciseTags(),
			"workouts" => getWorkouts()
		);
		return $response;
	}

	public function exerciseSeriesHistory () {
		include(app_path() . '/inc/functions.php');
		$series_id = json_decode(file_get_contents('php://input'), true)["series_id"];
		return getExerciseSeriesHistory($series_id);
	}

	public function specificExerciseEntries () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		$exercise_unit_id = json_decode(file_get_contents('php://input'), true)["exercise_unit_id"];
		return getSpecificExerciseEntries($date, $exercise_id, $exercise_unit_id);
	}

	public function journalEntry () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		return getJournalEntry($date);
	}

	public function autocompleteExercise () {
		include(app_path() . '/inc/functions.php');
		$exercise = json_decode(file_get_contents('php://input'), true)["exercise"];
		return autocompleteExercise($exercise);
	}

	public function autocompleteFood () {
		include(app_path() . '/inc/functions.php');
		$typing = json_decode(file_get_contents('php://input'), true)["typing"];
		return autocompleteFood($typing);
	}

	public function autocompleteMenu () {
		include(app_path() . '/inc/functions.php');
		$typing = json_decode(file_get_contents('php://input'), true)["typing"];		
		return autocompleteMenu($typing);
	}

	public function entries () {
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

	public function foodEntries () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		return getFoodEntries($date);
	}

	public function foodInfo () {
		include(app_path() . '/inc/functions.php');
		$food_id = json_decode(file_get_contents('php://input'), true)["id"];
		return getFoodInfo($food_id);
	}

	// public function exercises () {
	// 	// $array = getExercises($db);
	// }

	public function foodList () {
		include(app_path() . '/inc/functions.php');
		return getFoods();
	}

	public function recipeList () {
		include(app_path() . '/inc/functions.php');
		return getRecipes();
	}

	public function unitList () {

	}

	public function AllFoodsWithUnits () {
		include(app_path() . '/inc/functions.php');
		// $array = getFoodUnits($db);
		return getAllFoodsWithUnits();
	}

	public function weight () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		return getWeight($date);
	}

	public function recipeContents () {
		include(app_path() . '/inc/functions.php');
		$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];

		return array(
			'contents' => getRecipeContents($recipe_id),
			'steps' => getRecipeSteps($recipe_id)
		);
	}

}
