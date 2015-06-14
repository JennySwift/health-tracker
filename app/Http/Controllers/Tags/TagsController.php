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
	 * Delete tag from tags table. The tag was for a recipe.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function deleteRecipeTag(Request $request)
	{
		$id = $request->get('id');
		Tag::deleteRecipeTag($id);
		return Tag::getRecipeTags();
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
	
	/**
	 * Insert a new tag into the tags table, for recipes
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function insertRecipeTag(Request $request)
	{
		$name = $request->get('name');
		Tag::insertRecipeTag($name);
		return Tag::getRecipeTags();
	}
}
