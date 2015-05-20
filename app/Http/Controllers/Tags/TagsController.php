<?php namespace App\Http\Controllers\Tags;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use DB;
use Debugbar;

/**
 * Models
 */
use App\Models\Tags\Tag;
use App\Models\Exercises\Exercise;

class TagsController extends Controller {

	/**
	 * Exercises
	 */
	
	/**
	 * select
	 */
	
	/**
	 * insert
	 */
	
	public function InsertExerciseTag(Request $request)
	{
		//creates a new exercise tag
		$name = $request->get('name');
		
		Tag::insert([
			'name' => $name,
			'for' => 'exercise',
			'user_id' => Auth::user()->id
		]);

		return Tag::getExerciseTags();
	}
	
	/**
	 * update
	 */
	
	/**
	 * delete
	 */

	public function deleteExerciseTag(Request $request)
	{
		$id = $request->get('id');
		
		Tag::find($id)->delete();

		return Tag::getExerciseTags();
	}

	/**
	 * From old ExerciseTagController
	 */
	
	/**
	 * select
	 */
	
	/**
	 * insert
	 */
	
	/**
	 * Deletes all tags from the exercise then adds the correct tags to the exercise
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function insertTagsInExercise(Request $request)
	{
		$exercise = Exercise::find($request->get('exercise_id'));
		$tag_ids = $request->get('tags');
		
		//delete tags from the exercise
		//I wasn't sure if detach would work for this since I want to delete all tags that belong to the exercise.
		DB::table('taggables')->where('taggable_id', $exercise->id)->delete();

		//add tags to the exercise
		foreach ($tag_ids as $tag_id) {
			//add tag to the exercise
			$exercise->tags()->attach($tag_id, ['taggable_type' => 'exercise']);
		}

		return Exercise::getExercises();
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */

	public function deleteTagFromExercise(Request $request)
	{
		$exercise_id = $request->get('exercise_id');
		$tag_id = $request->get('tag_id');
		
		Tag
			::where('exercise_id', $exercise_id)
			->where('tag_id', $tag_id)
			->delete();

		return Exercise::getExercises();
	}

	/**
	 * Recipes
	 */
	
	public function insertRecipeTag(Request $request)
	{
		//creates a new recipe tag
		$name = $request->get('name');
		Tag::insertRecipeTag($name);
		return Recipe::getRecipeTags();
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
