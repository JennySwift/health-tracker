<?php namespace App\Http\Controllers\Exercises;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ExerciseTagController extends Controller {

	/**
	 * select
	 */
	
	/**
	 * insert
	 */
	
	public function insertTagsInExercise (Request $request) {
		//deletes all tags then adds the correct tags
		include(app_path() . '/inc/functions.php');
		$exercise_id = $request->get('exercise_id');
		$tags = $request->get('tags');
		
		//delete tags from exercise
		DB::table('exercise_tag')
			->where('exercise_id', $exercise_id)
			->delete();

		//insert tags in exercise
		foreach ($tags as $tag) {
			$tag_id = $tag['id'];
			insertExerciseTag($exercise_id, $tag_id);
		}

		return getExercises();
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */

	public function deleteTagFromExercise (Request $request) {
		include(app_path() . '/inc/functions.php');
		$exercise_id = $request->get('exercise_id');
		$tag_id = $request->get('tag_id');
		
		DB::table('exercise_tag')
			->where('exercise_id', $exercise_id)
			->where('tag_id', $tag_id)
			->delete();

		return getExercises();
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
