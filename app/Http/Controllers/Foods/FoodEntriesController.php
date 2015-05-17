<?php namespace App\Http\Controllers\Foods;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

use App\Models\Foods\Entry;

class FoodEntriesController extends Controller {
	/**
	 * select
	 */
	
	/**
	 * insert
	 */
	
	public function insertMenuEntry(Request $request)
	{
		$data = $request->get('data');
		$date = $data['date'];
		Entry::insertMenuEntry($data);

		$response = array(
			"food_entries" => Entry::getFoodEntries($date),
			"calories_for_the_day" => number_format(Calories::getCaloriesForTimePeriod($date, "day"), 2),
			"calories_for_the_week" => number_format(Calories::getCaloriesForTimePeriod($date, "week"), 2),
		);

		return $response;
	}
	
	// public function getFoodEntries(Request $request)
	// {
	// 	$date = $request->get('date');
	// 	return Entry::getFoodEntries($date);
	// }

	/**
	 * update
	 */
	
	public function deleteFoodEntry(Request $request)
	{
		$id = $request->get('id');
		$date = $request->get('date');
		Entry::where('id', $id)->delete();

		$response = array(
			"food_entries" => Entry::getFoodEntries($date),
			"calories_for_the_day" => number_format(Calories::getCaloriesForTimePeriod($date, "day"), 2),
			"calories_for_the_week" => number_format(Calories::getCaloriesForTimePeriod($date, "week"), 2)
		);
		return $response;
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
