<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

use Illuminate\Http\Request;

class DeleteController extends Controller {

	//
	public function foodFromRecipe () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];
		deleteFoodFromRecipe($id);
		return getRecipeContents($recipe_id);
	}

	public function tagFromExercise () {
		include(app_path() . '/inc/functions.php');
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		$tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
		deleteTagFromExercise($exercise_id, $tag_id);
		return getExercises();
	}

	public function exerciseTag () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		deleteExerciseTag($id);
		return getExerciseTags();
	}

	public function recipe () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		deleteRecipe($id);
		return getRecipes();
	}

	public function food () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		DB::table('foods')->where('id', $id)->delete();
		return getAllFoodsWithUnits();
	}

	public function exercise () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		DB::table('exercises')->where('id', $id)->delete();
		return getExercises();
	}

	public function exerciseUnit () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		DB::table('exercise_units')->where('id', $id)->delete();
		return getExerciseUnits();
	}

	public function foodUnit () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		DB::table('food_units')->where('id', $id)->delete();
		return getFoodUnits();
	}

	public function foodEntry () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		DB::table('food_entries')->where('id', $id)->delete();

		$response = array(
			"food_entries" => getFoodEntries($date),
			"calories_for_the_day" => number_format(getCaloriesForTimePeriod($date, "day"), 2),
			"calories_for_the_week" => number_format(getCaloriesForTimePeriod($date, "week"), 2)
		);
		return $response;
	}

	public function exerciseEntry () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		DB::table('exercise_entries')->where('id', $id)->delete();

		return getExerciseEntries($date);
	}

	public function unitFromCalories () {
		include(app_path() . '/inc/functions.php');
		$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
		$unit_id = json_decode(file_get_contents('php://input'), true)["unit_id"];
		deleteUnitFromCalories($food_id, $unit_id);
		return getFoodInfo($food_id);
	}

	public function item () {
		include(app_path() . '/inc/functions.php');
		
		$table = json_decode(file_get_contents('php://input'), true)["table"];
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		
		DB::table($table)->where('id', $id)->delete();    
	}

}
