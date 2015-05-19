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
		// return static::where('user_id', Auth::user()->id)->where('for', 'exercise')->get();
	}

	/**
	 * insert
	 */
	
	public static function insertRecipeTag($name)
	{
		static
			::insert([
				'name' => $name,
				'user_id' => Auth::user()->id
			]);
	}

	public static function insertTagsIntoRecipe($recipe_id, $tags)
	{
		foreach ($tags as $tag) {
			$tag_id = $tag['id'];
			static::insertTagIntoRecipe($recipe_id, $tag_id);
		}
	}

	public static function insertTagIntoRecipe($recipe_id, $tag_id)
	{
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

	public static function deleteRecipeTag($id)
	{
		static
			::where('id', $id)
			->delete();
	}

	public static function deleteTagsFromRecipe($recipe_id)
	{
		static
			::where('recipe_id', $recipe_id)
			->delete();
	}
	
}
