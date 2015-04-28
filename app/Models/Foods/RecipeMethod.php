<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use Auth;

class RecipeMethod extends Model {

	public static function insertRecipeMethod ($recipe_id, $steps) {
		$step_number = 0;
		foreach ($steps as $step_text) {
			$step_number++;

			static
				::insert([
					'recipe_id' => $recipe_id,
					'step' => $step_number,
					'text' => $step_text,
					'user_id' => Auth::user()->id
				]);
		}
	}

	public static function deleteRecipeMethod ($recipe_id) {
		static
			::where('recipe_id', $recipe_id)
			->delete();
	}

	public static function getRecipeSteps ($recipe_id) {
		$steps = static
			::where('recipe_id', $recipe_id)
			->select('step', 'text')
			->get();

		return $steps;
	}

}
