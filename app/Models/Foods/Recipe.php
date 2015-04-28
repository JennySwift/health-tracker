<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Recipe extends Model {

	public function recipeTags () {
		return $this->belongsToMany('App\Recipe_tag', 'recipe_tag', 'recipe_id', 'tag_id');
	}

	public static function insertRecipe ($name) {
		static
			::insert([
				'name' => $name,
				'user_id' => Auth::user()->id
			]);
	}

	public static function getRecipes () {
		$rows = static
			::where('user_id', Auth::user()->id)
			->select('id', 'name')
			->orderBy('name', 'asc')
			->get();
		

		$recipes = array();
		foreach ($rows as $row) {
			$recipe_id = $row->id;
			$recipe_name = $row->name;
			$tags = getTagsForRecipe($recipe_id);
			
			$recipes[] = array(
				"id" => $recipe_id,
				"name" => $recipe_name,
				"tags" => $tags
			);
		}
		return $recipes;
	}

	public static function deleteRecipe ($id) {
		static
			::where('id', $id)
			->delete();
	}
}
