<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use Auth;

class RecipeTag extends Model {

	protected $table = 'recipe_tag';
	
	public function recipes () {
		return $this->belongsToMany('App\Recipe', 'recipe_tag', 'recipe_id', 'tag_id');
	}

	public static function getTagsForRecipe ($recipe_id) {
		$tags = static
			::where('recipe_id', $recipe_id)
			->join('recipe_tags', 'tag_id', '=', 'recipe_tags.id')
			->select('tag_id as id', 'recipe_tags.name as name')
			->get();

		return $tags;
	}

	public static function deleteTagsFromRecipe ($recipe_id) {
		static
			::where('recipe_id', $recipe_id)
			->delete();
	}

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
}
