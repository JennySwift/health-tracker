<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class InsertController extends Controller {

	//
	public function something () {
		include(app_path() . '/inc/functions.php');

		// $table = $_GET['table'];
		$table = json_decode(file_get_contents('php://input'), true)["table"];
		// $date = $_GET['date'];
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		// $id = $_GET['id'];
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		// $name = $_GET['name'];
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		// $quantity = $_GET['quantity'];
		$quantity = json_decode(file_get_contents('php://input'), true)["quantity"];
		// $unit_id = $_GET['unit_id'];
		$unit_id = json_decode(file_get_contents('php://input'), true)["unit_id"];

		if ($table === "exercise_entries") {
			$sql = "INSERT INTO exercise_entries (date, exercise, quantity) VALUES ('$date', '$id', $quantity);";
		}

		elseif ($table === "food_entries") {
			$type = json_decode(file_get_contents('php://input'), true)["type"];

			if ($type === 'food') {
				$sql = "INSERT INTO food_entries (date, food, quantity, unit) VALUES ('$date', '$id', $quantity, $unit_id);";
			}
			elseif ($type === 'recipe') {
				// $recipe_contents = getRecipeContents($db, $id);
				$recipe_contents = json_decode(file_get_contents('php://input'), true)["recipe_contents"];

				insertRecipeEntry($db, $date, $id, $recipe_contents);
			}
		}
		elseif ($table === "recipe_entries") {
			$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
			$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];

			$sql = "INSERT INTO recipe_entries (recipe_id, food_id, quantity, unit) VALUES ($recipe_id, $food_id, $quantity, $unit_id);";
		}
		elseif ($table === "calories") {
			// $food_id = $_GET['food_id'];
			$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
			// $action = $_GET['action'];
			$checked_previously = json_decode(file_get_contents('php://input'), true)["checked_previously"];

			if ($checked_previously === false) {
				$sql = "INSERT INTO calories (unit_id, food_id) VALUES ($unit_id, $food_id);";
			}
			elseif ($checked_previously === true) {
				$sql = "DELETE FROM calories WHERE unit_id = $unit_id AND food_id = $food_id";
			}
			
		}
		elseif ($table === "weight") {
			$weight = json_decode(file_get_contents('php://input'), true)["weight"];

			$sql = "INSERT into weight (date, weight) VALUES ('$date', $weight) ON DUPLICATE KEY UPDATE weight = $weight;";
		}
		else {
			$sql = "INSERT INTO $table (name) VALUES ('$name');";
		}

		try {
		
		    $sql_result = $db->query($sql);
		
		    //=========================response=========================
		
		    $variables = get_defined_vars();
		
		    $response = array(
		        "variables" => $variables
		    );
		
		    $json = json_encode($response);
		    echo $json;
		}
		catch (Exception $e) {
		    $variables = get_defined_vars(); 
		    $json = json_encode($variables);
		    echo $json;
		    exit;
		}
	}
}
