<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SelectController extends Controller {

	//
	public function foodEntries () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		return getFoodEntries($date);
	}

	public function exercises () {
		// $array = getExercises($db);
	}

	// public function foodList () {
	// 	include(app_path() . '/inc/functions.php');
	// 	return getFoods();
	// }

	public function exerciseList () {

	}

	public function recipeList () {
		include(app_path() . '/inc/functions.php');
		return getRecipeList();
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
