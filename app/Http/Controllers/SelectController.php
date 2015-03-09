<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SelectController extends Controller {

	//
	public function pageLoad () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$response = array(
			"foods" => getFoods(),
			"recipes" => getRecipes(),
			"food_units" => getFoodUnits(),
			"foods_with_units" => getAllFoodsWithUnits(),
			"weight" => getWeight($date),
			"exercise_units" => getExerciseUnits(),
			"exercises" => getExercises(),
			"food_entries" => getFoodEntries($date),
			"exercise_entries" => getExerciseEntries($date)
		);
		return $response;
	}

	public function entries () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$response = array(
			"weight" => getWeight($date),
			"exercise_entries" => getExerciseEntries($date),

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

	}

}
