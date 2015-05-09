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
use App\User;

class FoodsController extends Controller {

	/**
	 * select
	 */

	public function getFoodInfo (Request $request) {
		$food_id = $request->get('food_id');
		return Food::getFoodInfo($food_id);
	}

	public function getAllFoodsWithUnits (Request $request) {
		return User::getAllFoodsWithUnits();
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
