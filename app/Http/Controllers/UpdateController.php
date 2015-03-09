<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UpdateController extends Controller {

	//
	public function defaultUnit () {

	}

	public function calories () {

	}

	public function something () {
		include(app_path() . '/inc/functions.php');
		$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
		$unit_id = json_decode(file_get_contents('php://input'), true)["unit_id"];
		$column = json_decode(file_get_contents('php://input'), true)["column"];

		if ($column === "default_unit") {
			$sql = "UPDATE calories SET default_unit = 'no' WHERE food_id = $food_id AND default_unit = 'yes';";
			
			$sql_result = $db->query($sql);

			$sql2 = "UPDATE calories SET default_unit = 'yes' WHERE food_id = $food_id AND unit_id = $unit_id;";

			$sql2_result = $db->query($sql2);
		}
		elseif ($column === "calories") {
			$calories = json_decode(file_get_contents('php://input'), true)["calories"];
			
			$sql = "UPDATE calories SET calories = $calories WHERE food_id = $food_id AND unit_id = $unit_id";
			$sql_result = $db->query($sql);
		}
			
	}
}
