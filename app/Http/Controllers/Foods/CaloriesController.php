<?php namespace App\Http\Controllers\Foods;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

use App\Models\Foods\Food;

class CaloriesController extends Controller {

	/**
	 * update
	 */
	
	public function updateCalories(Request $request)
	{
		$food_id = $request->get('food_id');
		$food = Food::find($request->get('food_id'));
		$unit_id = $request->get('unit_id');
		$calories = $request->get('calories');

		$food->units()
			->where('unit_id', $unit_id)
			->update(['calories' => $calories]);

		return Food::getFoodInfo($food_id);
	}

	public function insertUnitInCalories(Request $request)
	{
		$food_id = $request->get('food_id');
		$unit_id = $request->get('unit_id');
		Food::insertUnitInCalories($food, $unit_id);
		return Food::getFoodInfo($food_id);
	}

	/**
	 * delete
	 */
	
	public function deleteUnitFromCalories(Request $request)
	{
		$food = Food::find($request->get('food_id'));
		$unit_id = $request->get('unit_id');
		$food->units()->detach($unit_id);
		return Food::getFoodInfo($food_id);
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
