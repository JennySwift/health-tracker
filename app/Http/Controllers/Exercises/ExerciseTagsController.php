<?php namespace App\Http\Controllers\Exercises;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ExerciseTagsController extends Controller {

	/**
	 * select
	 */
	
	/**
	 * insert
	 */
	
	public function InsertExerciseTag (Request $request) {
		//creates a new exercise tag
		//I need functions.php for getExerciseTags
		include(app_path() . '/inc/functions.php');
		$name = $request->get('name');
		
		DB::table('exercise_tags')
			->insert([
				'name' => $name,
				'user_id' => Auth::user()->id
			]);

		return getExerciseTags();
	}
	
	/**
	 * update
	 */
	
	/**
	 * delete
	 */

	public function deleteExerciseTag (Request $request) {
		include(app_path() . '/inc/functions.php');
		$id = $request->get('id');
		
		DB::table('exercise_tags')
			->where('id', $id)
			->delete();

		return getExerciseTags();
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
