<?php namespace App\Http\Controllers\Foods;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Food;
use DB;
use Auth;
use Debugbar;

class FoodsController extends Controller {

	/**
	 * select
	 */
	
	public function autocompleteFood () {
		include(app_path() . '/inc/functions.php');
		$typing = json_decode(file_get_contents('php://input'), true)["typing"];
		return autocompleteFood($typing);
	}
	
	public function getFoodEntries () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		return getFoodEntries($date);
	}

	public function getFoodInfo () {
		include(app_path() . '/inc/functions.php');
		$food_id = json_decode(file_get_contents('php://input'), true)["id"];
		return getFoodInfo($food_id);
	}

	public function getFoodList () {
		include(app_path() . '/inc/functions.php');
		return getFoods();
	}

	public function getAllFoodsWithUnits () {
		include(app_path() . '/inc/functions.php');
		return getAllFoodsWithUnits();
	}

	/**
	 * insert
	 */

	public function insertMenuEntry () {
		include(app_path() . '/inc/functions.php');
		$data = json_decode(file_get_contents('php://input'), true);
		$date = $data['date'];
		insertMenuEntry($data);

		$response = array(
			"food_entries" => getFoodEntries($date),
			"calories_for_the_day" => number_format(getCaloriesForTimePeriod($date, "day"), 2),
			"calories_for_the_week" => number_format(getCaloriesForTimePeriod($date, "week"), 2),
		);

		return $response;
	}

	public function insertFood () {
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		insertFood($name);

		return getAllFoodsWithUnits();
	}

	public function insertFoodUnit () {

	}

	public function insertUnitInCalories () {
		include(app_path() . '/inc/functions.php');
		$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
		$unit_id = json_decode(file_get_contents('php://input'), true)["unit_id"];
		insertUnitInCalories($food_id, $unit_id);
		return getFoodInfo($food_id);
	}

	/**
	 * update
	 */
	
	public function updateDefaultUnit () {
		include(app_path() . '/inc/functions.php');
		$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
		$unit_id = json_decode(file_get_contents('php://input'), true)["unit_id"];

		updateDefaultUnit($food_id, $unit_id);

		return getFoodInfo($food_id);
	}

	public function updateCalories () {
		include(app_path() . '/inc/functions.php');
		$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
		$unit_id = json_decode(file_get_contents('php://input'), true)["unit_id"];
		$calories = json_decode(file_get_contents('php://input'), true)["calories"];
		
		updateCalories($food_id, $unit_id, $calories);

		return getFoodInfo($food_id);
	}

	/**
	 * delete
	 */

	public function deleteFood () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		DB::table('foods')->where('id', $id)->delete();
		return getAllFoodsWithUnits();
	}

	public function deleteFoodUnit () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		DB::table('food_units')->where('id', $id)->delete();
		return getFoodUnits();
	}

	public function deleteFoodEntry () {
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

	public function deleteUnitFromCalories () {
		include(app_path() . '/inc/functions.php');
		$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
		$unit_id = json_decode(file_get_contents('php://input'), true)["unit_id"];
		deleteUnitFromCalories($food_id, $unit_id);
		return getFoodInfo($food_id);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
