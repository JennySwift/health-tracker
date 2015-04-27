<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use Auth;

class RecipeTags extends Model {

	protected $table = 'recipe_tags';

	public static function getRecipeTags () {
		$recipe_tags = static
			::where('user_id', Auth::user()->id)
			->orderBy('name', 'asc')
			->select('id', 'name')
			->get();

		return $recipe_tags;
	}

	public static function insertRecipeTag ($name) {
		static
			::insert([
				'name' => $name,
				'user_id' => Auth::user()->id
			]);
	}

	public static function deleteRecipeTag ($id) {
		static
			::where('id', $id)
			->delete();
	}

}