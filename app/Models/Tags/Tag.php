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


}
