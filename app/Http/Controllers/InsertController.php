<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;

use Illuminate\Http\Request;

class InsertController extends Controller {

	//
	public function menuEntry () {
		include(app_path() . '/inc/functions.php');
		$data = json_decode(file_get_contents('php://input'), true);
		$date = $data['date'];
		$type = $data['type'];

		if ($type === 'food') {
			DB::table('food_entries')->insert([
				'date' => $date,
				'food_id' => $data['id'],
				'quantity' => $data['quantity'],
				'unit_id' => $data['unit_id'],
				'user_id' => Auth::user()->id
			]);
		}
		elseif ($type === 'recipe') {
			$recipe_contents = json_decode(file_get_contents('php://input'), true)["recipe_contents"];

			insertRecipeEntry($date, $id, $recipe_contents);
		}

		return getFoodEntries($date);
	}

	public function food () {
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		DB::table('foods')->insert([
			'name' => $name,
			'user_id' => Auth::user()->id
		]);
		return getAllFoodsWithUnits();
	}

	public function recipe () {

	}

	public function exercise () {
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		DB::table('exercises')->insert([
			'name' => $name,
			'user_id' => Auth::user()->id
		]);
		return getExercises();
	}

	public function foodUnit () {

	}

	public function exerciseEntries () {
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$sql = "INSERT INTO exercise_entries (date, exercise, quantity) VALUES ('$date', '$id', $quantity);";
	}

	public function recipeItem () {

	}

	public function recipeEntries () {
		$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
		$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];

		$sql = "INSERT INTO recipe_entries (recipe_id, food_id, quantity, unit) VALUES ($recipe_id, $food_id, $quantity, $unit_id);";
	}

	public function unitInCalories () {
		include(app_path() . '/inc/functions.php');
		$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
		$unit_id = json_decode(file_get_contents('php://input'), true)["unit_id"];
		insertUnitInCalories($food_id, $unit_id);
		return getFoodInfo($food_id);
	}

	public function weight () {
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$weight = json_decode(file_get_contents('php://input'), true)["weight"];

		$sql = "INSERT into weight (date, weight) VALUES ('$date', $weight) ON DUPLICATE KEY UPDATE weight = $weight;";
	}

	public function item () {
		include(app_path() . '/inc/functions.php');
		$table = json_decode(file_get_contents('php://input'), true)["table"];
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		$sql = "INSERT INTO $table (name) VALUES ('$name');";
	}
}
