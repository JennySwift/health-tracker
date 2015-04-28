<?php namespace App\Http\Controllers\Exercises;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Models\Exercises\Unit as ExerciseUnit;

class ExerciseUnitsController extends Controller {

	/**
	 * select
	 */
	
	/**
	 * insert
	 */
	
	public function insertExerciseUnit (Request $request) {
		$name = $request->get('name');
		
		ExerciseUnit::insert([
			'name' => $name,	
			'user_id' => Auth::user()->id
		]);

		return ExerciseUnit::getExerciseUnits();
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */

	public function deleteExerciseUnit (Request $request) {
		$id = $request->get('id');

		ExerciseUnit::where('id', $id)->delete();
		return ExerciseUnit::getExerciseUnits();
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
