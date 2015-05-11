<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Tags\Tag;
use App\Models\Foods\RecipeMethod;
use App\Models\Foods\Food;
use App\Models\Units\Unit;
use App\Models\Foods\Calories;

class Recipe extends Model {

    protected $fillable = ['name'];

	/**
	 * Define relationships
	 */

	public function user () {
		return $this->belongsTo('App\User');
	}

	public function steps () {
		return $this->hasMany('App\Models\Foods\RecipeMethod');
	}

	public function foods () {
		return $this->belongsToMany('App\Models\Foods\Food', 'food_recipe', 'food_id', 'recipe_id');
	}

	public function tags () {
		return $this->belongsToMany('App\Models\Tags\Tag', 'taggables', 'tag_id', 'taggable_id');
	}

	public function entries () {
		return $this->hasMany('App\Models\Foods\FoodEntry');
	}

	/**
	 * select
	 */

	/**
	 * Get all recipes, along with their tags, for the user, and that match the $name filter
	 * @param  [type] $name    [description]
	 * @param  [type] $tag_ids [description]
	 * @return [type]          [description]
	 */
	public static function filterRecipes ($name, $tag_ids) {
		$recipes = static::where('recipes.user_id', Auth::user()->id);

		//filter by name
		if ($name !== '') {
			$name = '%' . $name . '%';

			$recipes = $recipes
				->where('name', 'LIKE', $name);
		}
		
		//filter by tags
		if (count($tag_ids) > 0) {
			foreach ($tag_ids as $tag_id) {
			    $recipes = $recipes->whereHas('tags', function ($q) use ($tag_id) {
			        $q->where('tags.id', $tag_id); 
			    });
			}
		}
		
		$recipes = $recipes
			->select('id', 'name')
			->orderBy('name', 'asc')
			->get();

		$array = array();
		foreach ($recipes as $recipe) {
			$id = $recipe->id;
			$name = $recipe->name;
			$tags = static::getRecipeTags($id);
			
			$array[] = array(
				"id" => $id,
				"name" => $name,
				"tags" => $tags
			);
		}

		return $array;
	}

	public static function getRecipeTags ($recipe_id) {
		$recipe = static::find($recipe_id);
		$tags = $recipe->tags()->orderBy('name', 'asc')->get();
		return $tags;
	}

	/**
	 * This probably needs doing after refactor
	 * @param  [type] $recipe_id [description]
	 * @return [type]            [description]
	 */
	public static function getRecipeContents ($recipe_id) {
		$recipe_contents = static
			::where('recipe_id', $recipe_id)
			->join('foods', 'food_recipe.food_id', '=', 'foods.id')
			->join('units', 'food_recipe.unit_id', '=', 'units.id')
			->select('food_recipe.id', 'food_recipe.description', 'foods.name AS food_name', 'units.name AS unit_name', 'recipe_id', 'food_id', 'quantity', 'unit_id')
			->get();

		foreach ($recipe_contents as $item) {
			$food = Food::find($item->food_id);
			$assoc_units = $food->units;
			$item->assoc_units = $assoc_units;
		}
		
		return $recipe_contents;
	}


	/**
	 * insert
	 */
	
	public static function insertRecipe ($name) {
		static
			::insert([
				'name' => $name,
				'user_id' => Auth::user()->id
			]);
	}

	public static function insertQuickRecipe ($recipe_name, $contents, $steps, $check_similar_names) {
		$similar_names = array();
		$data_to_insert = array();
		//$index is so if a similar name is found, I know what index it is in the quick recipe array for the javascript.
		$index = -1;

		//$contents needs to have: food_name, unit_name, quantity, maybe description
		foreach ($contents as $item) {
			$food_name = $item['food_name'];
			$unit_name = $item['unit_name'];
			$quantity = $item['quantity'];
			$index++;

			if (isset($item['description'])) {
				$description = $item['description'];
			}
			else {
				$description = null;
			}

			if ($check_similar_names) {
				$found = static::checkSimilarNames($food_name, 'foods');

				if ($found) {
					$similar_names['foods'][] = array(
						'specified_food' => array('name' => $food_name),
						'existing_food' => array('name' => $found),
						'checked' => $found,
						'index' => $index
					);
				}

				$found = static::checkSimilarNames($unit_name, 'food_units');

				if ($found) {
					$similar_names['units'][] = array(
						'specified_unit' => array('name' => $unit_name),
						'existing_unit' => array('name' => $found),
						'checked' => $found,
						'index' => $index
					);
				}
			}
			if (!$check_similar_names || count($similar_names) === 0) {
				//we can insert things now that no similar names were found, or we have already checked for similar names previously.
			
				//retrieve the id if the food exists, insert and retrieve the id if the food does not exist
				$food_id = Food::insertFoodIfNotExists($food_name);
				//same for the unit
				$unit_id = Unit::insertUnitIfNotExists($unit_name);

				//add the item to the array for inserting when all items are in the array
				$data_to_insert[] = array(
					'food_id' => $food_id,
					'unit_id' => $unit_id,
					'quantity' => $quantity,
					'description' => $description
				);
			}
		}

		if (count($similar_names) > 0) {
			return $similar_names;
		}

		//insert recipe into recipes table
		$recipe_id = static::insertQuickRecipeRecipe($recipe_name);

		//insert the method for the recipe
		RecipeMethod::insertRecipeMethod($recipe_id, $steps);

		//insert the items into food_recipe table
		foreach ($data_to_insert as $item) {
			//insert a row into food_recipe table
			Recipe::insertFoodIntoRecipe($recipe_id, $item);

			//insert food and unit ids into calories table (if the row doesn't exist already in the table) so that the unit is an associated unit of the food
			$count = Calories
				::where('food_id', $item['food_id'])
				->where('unit_id', $item['unit_id'])
				->where('user_id', Auth::user()->id)
				->count();

			if ($count === 0) {
				Calories::insertUnitInCalories($item['food_id'], $item['unit_id']);
			}	
		}
	}

	public static function insertQuickRecipeRecipe ($name) {
		//insert recipe into recipes table and retrieve the id
		$id = static
			::insertGetId([
				'name' => $name,
				'user_id' => Auth::user()->id
			]);

		return $id;
	}

	/**
	 * This probably needs doing after refactor
	 * @param  [type] $recipe_id [description]
	 * @param  [type] $data      [description]
	 * @return [type]            [description]
	 */
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

	/**
	 * update
	 */
	
	/**
	 * delete
	 */

	public static function deleteRecipe ($id) {
		static
			::where('id', $id)
			->delete();
	}

	/**
	 * other
	 */

	public static function checkSimilarNames ($name, $table) {
		//for quick recipe
		include(app_path() . '/inc/functions.php');
		$count = countItem($table, $name);

		if ($count < 1) {
			//the name does not exist

			if (substr($name, -3) === 'ies') {
				//the name ends in 'ies'. check if it's singular form exists.
				$similar_name = substr($name, 0, -3) . 'y';
				$found = pluckName($similar_name, $table);
			}
			elseif (substr($name, -1) === 'y') {
				//the name ends in 'y'. Check if it's plural form exists.
				$similar_name = substr($name, 0, -1) . 'ies';
				$found = pluckName($similar_name, $table);
			}

			elseif (substr($name, -1) === 's' && !isset($found)) {
				//the name ends in s. check if its singular form is in the database
				$similar_name = substr($name, 0, -1);
				$found = pluckName($similar_name, $table);

				//if nothing was found, check if its plural form is in the database
				if (!isset($found)) {
					$similar_name = $name . 'es';
					$found = pluckName($similar_name, $table);
				}
			}

			//check if it's plural form exists if no singular forms were found
			if (!isset($found)) {
				$similar_name = $name . 's';
				$found = pluckName($similar_name, $table);
			}
		}
		if (isset($found)) {
			return $found;
		}
	}

	
}
