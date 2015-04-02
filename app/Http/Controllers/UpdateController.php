<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Debugbar;

class UpdateController extends Controller {

	//
	public function defaultUnit () {
		include(app_path() . '/inc/functions.php');
		$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
		$unit_id = json_decode(file_get_contents('php://input'), true)["unit_id"];

		updateDefaultUnit($food_id, $unit_id);

		return getFoodInfo($food_id);
	}

	public function exerciseStepNumber () {
		include(app_path() . '/inc/functions.php');
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		$step_number = json_decode(file_get_contents('php://input'), true)["step_number"];
		updateExerciseStepNumber($exercise_id, $step_number);
		return getExercises();
	}

	public function exerciseSeries () {
		include(app_path() . '/inc/functions.php');
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		$series_id = json_decode(file_get_contents('php://input'), true)["series_id"];

		updateExerciseSeries($exercise_id, $series_id);

		return getExercises();
	}

	public function defaultExerciseQuantity () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		$quantity = json_decode(file_get_contents('php://input'), true)["quantity"];
		updateDefaultExerciseQuantity($id, $quantity);
		return getExercises();
	}

	// public function journalEntry () {
	// 	include(app_path() . '/inc/functions.php');
	// 	$date = json_decode(file_get_contents('php://input'), true)["date"];
	// 	$id = json_decode(file_get_contents('php://input'), true)["id"];
	// 	$text = json_decode(file_get_contents('php://input'), true)["text"];
	// 	updateJournalEntry($id, $text);

	// 	// $journal_entry = getJournalEntry($date);
	// 	// Debugbar::info('journal_entry: ', $journal_entry);
		
	// 	// return getJournalEntry($date);
	// }

	public function defaultExerciseUnit () {
		include(app_path() . '/inc/functions.php');
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		$default_exercise_unit_id = json_decode(file_get_contents('php://input'), true)["default_exercise_unit_id"];

		updateDefaultExerciseUnit($exercise_id, $default_exercise_unit_id);

		return getExercises();
	}

	public function calories () {
		include(app_path() . '/inc/functions.php');
		$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
		$unit_id = json_decode(file_get_contents('php://input'), true)["unit_id"];
		$calories = json_decode(file_get_contents('php://input'), true)["calories"];
		
		updateCalories($food_id, $unit_id, $calories);

		return getFoodInfo($food_id);
	}
}
