<?php namespace App\Http\Controllers\Weights;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class WeightsController extends Controller {

	public function weight () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$weight = json_decode(file_get_contents('php://input'), true)["weight"];

		if (getWeight($date)) {
			//This date already has a weight entry. We are updating, not inserting.
			updateWeight($date, $weight);
		}
		else {
			//we are inserting
			insertWeight($date, $weight);
		}
		return getWeight($date);
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
