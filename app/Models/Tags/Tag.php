<?php namespace App\Models\Tags;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;
use Auth;

class Tag extends Model {

	use OwnedByUser;

	/**
	 * Define relationships
	 */
	
	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function recipes()
	{
		return $this->belongsToMany('App\Models\Foods\Recipe', 'recipe_tag', 'tag_id', 'taggable_id');
	}

	public function exercises()
	{
		return $this->belongsToMany('App\Models\Exercises\Exercise', 'taggables', 'tag_id', 'taggable_id');
	}

	/**
	 * select
	 */
	
	public static function getExerciseTags()
	{
		return static::forCurrentUser()->where('for', 'exercise')->get();
	}

	/**
	 * Get all the user's tags for recipes
	 * @return [type] [description]
	 */
	public static function getRecipeTags()
	{
		return static::forCurrentUser()->where('for', 'recipe')->get();
	}

	/**
	 * insert
	 */
	
	/**
	 * Inserts a new tag (of type recipe) into the tags table
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public static function insertRecipeTag($name)
	{
		static
			::insert([
				'name' => $name,
				'for' => 'recipe',
				'user_id' => Auth::user()->id
			]);
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */

	/**
	 * Deletes a recipe tag from the tags table
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public static function deleteRecipeTag($id)
	{
		static
			::where('id', $id)
			->delete();
	}

}
