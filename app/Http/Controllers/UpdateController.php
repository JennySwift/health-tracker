<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UpdateController extends Controller {

	//
	public function defaultUnit () {
		include(app_path() . '/inc/functions.php');
		$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
		$unit_id = json_decode(file_get_contents('php://input'), true)["unit_id"];

		updateDefaultUnit($food_id, $unit_id);

		return getFoodInfo($food_id);
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
