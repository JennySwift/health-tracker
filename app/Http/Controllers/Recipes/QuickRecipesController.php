<?php namespace App\Http\Controllers\Recipes;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Debugbar;
use DB;
use Auth;

use Illuminate\Http\Request;

/**
 * Models
 */
use App\Models\Foods\Food;
use App\Models\Foods\Recipe;
use App\Models\Foods\RecipeMethod;
use App\Models\Units\Unit;

class QuickRecipesController extends Controller {

	/**
	 * select
	 */
	
	private function pluckName($name, $table)
	{
		//for quick recipe
		$name = DB::table($table)
			->where('name', $name)
			->where('user_id', Auth::user()->id)
			->pluck('name');

		return $name;
	}

	/**
	 * insert
	 */
	
	/**
	 * This is the function that is called from the ajax request.
	 * Check for similar names.
	 * If they are found, return them.
	 * If not, insert the recipe.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function quickRecipe(Request $request)
	{	
		$recipe = $request->get('recipe');
		$recipe_name = $recipe['name'];
		$steps = $recipe['steps'];
		$contents = $recipe['items'];

		if ($request->get('check_similar_names')) {
			//We are checking for similar names
			$similar_names = $this->checkEntireRecipeForSimilarNames($contents);

			if (isset($similar_names['foods']) || isset($similar_names['units'])) {
				//Similar names were found, so return them
				return array(
					'similar_names' => $similar_names
				);
			}
			else {
				//We have checked, but no similar names were found.
				//So insert the recipe.
				//Then return the recipes and foods and units.
				$data_to_insert = $this->populateArrayBeforeInserting($contents);

				return $this->insertEverything($recipe_name, $steps, $data_to_insert);
			}	
		}
		else {
			//We are not checking for similar names,
			//so we are inserting the recipe
			$data_to_insert = $this->populateArrayBeforeInserting($contents);

			return $this->insertEverything($recipe_name, $steps, $data_to_insert);
		}
	}

	private function checkEntireRecipeForSimilarNames($contents)
	{
		//$index is so if a similar name is found,
		//I know what index it is in the quick recipe array for the javascript.
		$index = -1;
		$similar_names = [];

		foreach ($contents as $item) {
			$index++;
			$food_name = $item['food'];
			$unit_name = $item['unit'];

			if (isset($item['description'])) {
				$description = $item['description'];
			}
			else {
				$description = null;
			}

			$similar_food_info = $this->populateSimilarFoodNames($food_name, $index);

			if (count($similar_food_info) > 0) {
				$similar_names['foods'][] = $similar_food_info;
			}

			$similar_unit_info = $this->populateSimilarUnitNames($unit_name, $index);

			if (count($similar_unit_info) > 0) {
				$similar_names['units'][] = $similar_unit_info;
			}
			
			
		}
		// dd($similar_names);
		return $similar_names;
	}

	private function populateSimilarUnitNames($unit_name, $index)
	{
		$similar_unit_names = [];
		$found = $this->checkSimilarNames($unit_name, 'units');

		if ($found) {
			$similar_unit_names = [
				'specified_unit' => array('name' => $unit_name),
				'existing_unit' => array('name' => $found),
				'checked' => $found,
				'index' => $index
			];
		}

		return $similar_unit_names;
	}

	private function populateSimilarFoodNames($food_name, $index)
	{
		$similar_food_names = [];
		$found = $this->checkSimilarNames($food_name, 'foods');

		if ($found) {
			$similar_food_names = [
				'specified_food' => array('name' => $food_name),
				'existing_food' => array('name' => $found),
				'checked' => $found,
				'index' => $index
			];
		}
		// dd($similar_food_names);
		return $similar_food_names;
	}

	/**
	 * We can insert things now that no similar names were found,
	 * or we have already checked for similar names previously.
	 * @param  [type] $food_name [description]
	 * @param  [type] $unit_name [description]
	 * @return [type]            [description]
	 */
	private function populateArrayBeforeInserting($contents)
	{		
		$data_to_insert = [];

		foreach ($contents as $item) {
			$food_name = $item['food'];
			$unit_name = $item['unit'];
			$quantity = $item['quantity'];

			if (isset($item['description'])) {
				$description = $item['description'];
			}
			else {
				$description = null;
			}

			//retrieve the id if the food exists,
			//insert and retrieve the id if the food does not exist
			$food_id = Food::insertFoodIfNotExists($food_name);
			//same for the unit
			$unit_id = Unit::insertUnitIfNotExists($unit_name);

			//add the item to the array for inserting later, when all items have been added to the array
			$data_to_insert[] = array(
				'food_id' => $food_id,
				'unit_id' => $unit_id,
				'quantity' => $quantity,
				'description' => $description
			);

		}

		return $data_to_insert;
	}

	private function insertEverything($recipe_name, $steps, $data_to_insert)
	{
		//insert recipe into recipes table
		$recipe = Recipe::find($this->insertRecipe($recipe_name));

		//insert the method for the recipe
		RecipeMethod::insertRecipeMethod($recipe, $steps);

		//insert the items into food_recipe table
		foreach ($data_to_insert as $item) {
			//insert a row into food_recipe table
			Recipe::insertFoodIntoRecipe($recipe, $item);

			//insert food and unit ids into calories table (if the row doesn't exist already in the table) so that the unit is an associated unit of the food
			$count = DB::table('food_unit')
				->where('food_id', $item['food_id'])
				->where('unit_id', $item['unit_id'])
				->where('user_id', Auth::user()->id)
				->count();

			if ($count === 0) {
				$food = Food::find($item['food_id']);
				Food::insertUnitInCalories($food, $item['unit_id']);
			}	
		}

		return array(
			'recipes' => Recipe::filterRecipes('', []),
			'foods_with_units' => Food::getAllFoodsWithUnits(),
			'food_units' => Unit::getFoodUnits()
		);
	}

	private function insertRecipe($name)
	{
		//insert recipe into recipes table and retrieve the id
		$id = Recipe
			::insertGetId([
				'name' => $name,
				'user_id' => Auth::user()->id
			]);

		return $id;
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */
	
	/**
	 * other
	 */
	
	private function countItem($table, $name)
	{
		$count = DB::table($table)
			->where('name', $name)
			->where('user_id', Auth::user()->id)
			->count();

		return $count;
	}
	
	/**
	 * Currently this checks the units table for similar names.
	 * I should change it to find them only if the unit type is for food.
	 * @param  [type] $name  [description]
	 * @param  [type] $table [description]
	 * @return [type]        [description]
	 */
	private function checkSimilarNames($name, $table)
	{
		//for quick recipe
		$count = $this->countItem($table, $name);

		if ($count < 1) {
			//the name does not exist

			if (substr($name, -3) === 'ies') {
				//the name ends in 'ies'. check if it's singular form exists.
				$similar_name = substr($name, 0, -3) . 'y';
				$found = $this->pluckName($similar_name, $table);
			}
			elseif (substr($name, -1) === 'y') {
				//the name ends in 'y'. Check if it's plural form exists.
				$similar_name = substr($name, 0, -1) . 'ies';
				$found = $this->pluckName($similar_name, $table);
			}

			elseif (substr($name, -1) === 's' && !isset($found)) {
				//the name ends in s. check if its singular form is in the database
				$similar_name = substr($name, 0, -1);
				$found = $this->pluckName($similar_name, $table);

				//if nothing was found, check if its plural form is in the database
				if (!isset($found)) {
					$similar_name = $name . 'es';
					$found = $this->pluckName($similar_name, $table);
				}
			}

			//check if it's plural form exists if no singular forms were found
			if (!isset($found)) {
				$similar_name = $name . 's';
				$found = $this->pluckName($similar_name, $table);
			}
		}
		if (isset($found)) {
			return $found;
		}
	}

}