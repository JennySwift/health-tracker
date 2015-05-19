<?php namespace App\Http\Controllers\Exercises;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

use App\Models\Exercises\Series;

class ExerciseSeriesController extends Controller {

	/**
	 * select
	 */
	
	/**
	 * insert
	 */
	
	public function insertExerciseSeries(Request $request)
	{
		$name = $request->get('name');
		
		Series
			::insert([
				'name' => $name,
				'user_id' => Auth::user()->id
			]);

		return Series::getExerciseSeries();
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */
	
	public function deleteExerciseSeries(Request $request)
	{
		$id = $request->get('id');
		
		Series::where('id', $id)->delete();

		return Series::getExerciseSeries();
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
