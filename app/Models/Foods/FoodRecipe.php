<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use Auth;

class FoodRecipe extends Model {

	public static function insertFoodIntoRecipe ($recipe_id, $data) {
		if (isset($data['description'])) {
			$description = $data['description'];
		}
		else {
			$description = null;
		}

		static
			::insert([
				'recipe_id' => $recipe_id,
				'food_id' => $data['food_id'],
				'unit_id' => $data['unit_id'],
				'quantity' => $data['quantity'],
				'description' => $description,
				'user_id' => Auth::user()->id
			]);
	}

	public static function deleteFoodFromRecipe ($id) {
		static
			::where('id', $id)
			->delete();
	}

	public static function getRecipeContents ($recipe_id) {
		$recipe_contents = static
			::where('recipe_id', $recipe_id)
			->join('foods', 'food_recipe.food_id', '=', 'foods.id')
			->join('food_units', 'food_recipe.unit_id', '=', 'food_units.id')
			->select('food_recipe.id', 'food_recipe.description', 'foods.name AS food_name', 'food_units.name AS unit_name', 'recipe_id', 'food_id', 'quantity', 'unit_id')
			->get();

		foreach ($recipe_contents as $item) {
			$food_id = $item->food_id;
			$assoc_units = getAssocUnits($food_id);
			$item->assoc_units = $assoc_units;
		}
		
		return $recipe_contents;
	}

}
