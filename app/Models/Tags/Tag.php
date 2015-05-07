<?php namespace App\Models\Tags;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Tag extends Model {

	/**
	 * Define relationships
	 */
	
	public function user () {
		return $this->belongsTo('App\User');
	}

	public function recipes () {
		return $this->belongsToMany('App\Models\Foods\Recipe', 'recipe_tag', 'recipe_id', 'tag_id');
	}

	/**
	 * exercise tags
	 */

	public static function getExerciseTags () {
		//gets all exercise tags
		$tags = static
			::where('user_id', Auth::user()->id)
			->select('id', 'name')
			->get();

		return $tags;
	}

	/**
	 * recipe tags
	 */
	
	/**
	 * select
	 */
	
	public static function getRecipeTags () {
		$recipe_tags = static
			::where('user_id', Auth::user()->id)
			->orderBy('name', 'asc')
			->select('id', 'name')
			->get();

		return $recipe_tags;
	}

	/**
	 * insert
	 */
	
	public static function insertRecipeTag ($name) {
		static
			::insert([
				'name' => $name,
				'user_id' => Auth::user()->id
			]);
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */

	public static function deleteRecipeTag ($id) {
		static
			::where('id', $id)
			->delete();
	}





	/**
	 * code from old RecipeTag model
	 */
	
	/**
	 * select
	 */
	
	/**
	 * I still need to update this function after changing the database schema
	 */
	
	public static function getTagsForRecipe ($recipe_id) {
		// $tags = static
		// 	::where('recipe_id', $recipe_id)
		// 	->join('recipe_tags', 'tag_id', '=', 'recipe_tags.id')
		// 	->select('tag_id as id', 'recipe_tags.name as name')
		// 	->get();

		// return $tags;
	}


	/**
	 * insert
	 */
	
	public static function insertTagsIntoRecipe ($recipe_id, $tags) {
		foreach ($tags as $tag) {
			$tag_id = $tag['id'];
			insertTagIntoRecipe($recipe_id, $tag_id);
		}
	}

	public static function insertTagIntoRecipe ($recipe_id, $tag_id) {
		static
			::insert([
				'recipe_id' => $recipe_id,
				'tag_id' => $tag_id,
				'user_id' => Auth::user()->id
			]);
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */

	public static function deleteTagsFromRecipe ($recipe_id) {
		static
			::where('recipe_id', $recipe_id)
			->delete();
	}




	/**
	 * code from old ExerciseTag model
	 */
	
	/**
	 * select
	 */
	
	/**
	 * This needs redoing after changing database schema
	 */
	
	public static function getTagsForExercise ($exercise_id) {
		//gets tags associated with each exercise
		// $tags = static
		// 	::where('exercise_id', $exercise_id)
		// 	->join('tags', 'exercise_tag.tag_id', '=', 'tags.id')
		// 	->select('tags.id', 'name')
		// 	->get();

		// return $tags;
	}

	/**
	 * insert
	 */

	public static function insertExerciseTag ($exercise_id, $tag_id) {
		//inserts a tag into an exercise
		// static::insert([
		// 	'exercise_id' => $exercise_id,
		// 	'tag_id' => $tag_id,
		// 	'user_id' => Auth::user()->id
		// ]);
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */


}
