<?php namespace App\Http\Controllers\Foods;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Foods\Food;
use App\Models\Foods\FoodEntry;
use App\Models\Units\Unit;
use App\Models\Foods\Calories;
use DB;
use Auth;
use Debugbar;

class FoodsController extends Controller {

	/**
	 * select
	 */
	
	public function autocompleteFood (Request $request) {
		$typing = '%' . $request->get('typing') . '%';

		$foods = Food
			::where('name', 'LIKE', $typing)
			->where('user_id', Auth::user()->id)
			->select('id', 'name')
			->get();
		   
		return $foods;
	}

	public function getFoodInfo (Request $request) {
		$food_id = $request->get('food_id');

		$default_unit = Calories::getDefaultUnit($food_id);
		$food_units = Unit::getFoodUnits();
		$assoc_units = Unit::getAssocUnits($food_id);
		$units = array();

		//checking to see if the unit has already been given to a food, so that it appears checked.
		foreach ($food_units as $food_unit) {
			$unit_id = $food_unit->id;
			$unit_name = $food_unit->name;
			$match = 0;

			foreach ($assoc_units as $assoc_unit) {
				$assoc_unit_id = $assoc_unit['id'];
				$calories = $assoc_unit['calories'];

				if ($unit_id == $assoc_unit_id) {
					$match++;
				}
			}
			if ($match === 1) {
				$calories = Calories::getCalories($food_id, $unit_id);

				if ($unit_id === $default_unit) {
					$units[] = array(
						"id" => $unit_id,
						"name" => $unit_name,
						"checked" => true,
						"default_unit" => true,
						"calories" => $calories
					);
				}
				else {
					$units[] = array(
						"id" => $unit_id,
						"name" => $unit_name,
						"checked" => true,
						"default_unit" => false,
						"calories" => $calories
					);
				}
			}
			else {
				$units[] = array(
					"id" => $unit_id,
					"name" => $unit_name,
					"checked" => false,
					"default_unit" => false
				);
			}
		}

		return $units;
	}

	public function getAllFoodsWithUnits (Request $request) {
		return Food::getAllFoodsWithUnits();
	}

	/**
	 * insert
	 */

	public function insertFood (Request $request) {
		$name = $request->get('name');
		Food::insertFood($name);

		return Food::getAllFoodsWithUnits();
	}

	/**
	 * update
	 */

	/**
	 * delete
	 */

	public function deleteFood (Request $request) {
		$id = $request->get('id');
		Food::where('id', $id)->delete();
		return Food::getAllFoodsWithUnits();
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
