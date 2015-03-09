<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

use Illuminate\Http\Request;

class DeleteController extends Controller {

	//
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

	public function foodEntry () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		DB::table('food_entries')->where('id', $id)->delete();
		return getFoodEntries($date);
	}

	public function item () {
		include(app_path() . '/inc/functions.php');
		
		$table = json_decode(file_get_contents('php://input'), true)["table"];
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		
		DB::table($table)->where('id', $id)->delete();    
	}

}
