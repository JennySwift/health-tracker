<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

/**
 * Models
 */
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

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function steps()
	{
		return $this->hasMany('App\Models\Foods\RecipeMethod');
	}

	public function foods()
	{
		return $this->belongsToMany('App\Models\Foods\Food', 'food_recipe', 'recipe_id', 'food_id');
	}

	public function tags()
	{
		return $this->belongsToMany('App\Models\Tags\Tag', 'taggables', 'taggable_id', 'tag_id')->where('taggable_type', 'recipe');
	}

	public function entries()
	{
		return $this->hasMany('App\Models\Foods\Entry');
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
	public static function filterRecipes($name, $tag_ids)
	{
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

	/**
	 * Get recipe contents and steps.
	 * Contents should include the foods that belong to the recipe,
	 * along with the description, quantity, and unit for the food when used in the recipe (from food_recipe table),
	 * and with the tags for the recipe.
	 * Redoing after refactor. Still need description, quantity, unit.
	 * @param  [type] $recipe_id [description]
	 * @return [type]            [description]
	 */
	public static function getRecipeInfo($recipe)
	{
		/**
		 * @VP:
		 * Why can't I do:
		 * $recipe_info = static::where('id', $recipe->id)
		 * ->with('foods')
		 * ->with('steps')
		 * ->get();
		 * and then dd($recipe_info->foods)?
		 * It gives an error.
		 */
		
		$contents = DB::table('food_recipe')
			->where('recipe_id', $recipe->id)
			->join('foods', 'food_id', '=', 'foods.id')
			->join('units', 'unit_id', '=', 'units.id')
			->select('foods.name', 'units.name as unit_name', 'quantity', 'description')
			->get();

		// dd($contents);
		
		return [
			'recipe' => $recipe,
			'contents' => $contents,
			'steps' => $recipe->steps,
			'tags' => $recipe->tags
		];
	}

	/**
	 * Get all tags that belong to a recipe
	 * @param  [type] $recipe_id [description]
	 * @return [type]            [description]
	 */
	public static function getRecipeTags($recipe_id)
	{
		$recipe = static::find($recipe_id);
		$tags = $recipe->tags()->orderBy('name', 'asc')->get();
		return $tags;
	}

	public static function countItem($table, $name)
	{
		$count = DB::table($table)
			->where('name', $name)
			->where('user_id', Auth::user()->id)
			->count();

		return $count;
	}

	public static function pluckName($name, $table)
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
	
	public static function insertRecipe($name)
	{
		static
			::insert([
				'name' => $name,
				'user_id' => Auth::user()->id
			]);
	}

	public static function insertQuickRecipe($recipe_name, $contents, $steps, $check_similar_names)
	{
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

	public static function insertQuickRecipeRecipe($name)
	{
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
	public static function insertFoodIntoRecipe($recipe_id, $data)
	{
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

	public static function deleteRecipe($id)
	{
		static
			::where('id', $id)
			->delete();
	}

	/**
	 * other
	 */

	public static function checkSimilarNames($name, $table)
	{
		//for quick recipe
		$count = static::countItem($table, $name);

		if ($count < 1) {
			//the name does not exist

			if (substr($name, -3) === 'ies') {
				//the name ends in 'ies'. check if it's singular form exists.
				$similar_name = substr($name, 0, -3) . 'y';
				$found = static::pluckName($similar_name, $table);
			}
			elseif (substr($name, -1) === 'y') {
				//the name ends in 'y'. Check if it's plural form exists.
				$similar_name = substr($name, 0, -1) . 'ies';
				$found = static::pluckName($similar_name, $table);
			}

			elseif (substr($name, -1) === 's' && !isset($found)) {
				//the name ends in s. check if its singular form is in the database
				$similar_name = substr($name, 0, -1);
				$found = static::pluckName($similar_name, $table);

				//if nothing was found, check if its plural form is in the database
				if (!isset($found)) {
					$similar_name = $name . 'es';
					$found = static::pluckName($similar_name, $table);
				}
			}

			//check if it's plural form exists if no singular forms were found
			if (!isset($found)) {
				$similar_name = $name . 's';
				$found = static::pluckName($similar_name, $table);
			}
		}
		if (isset($found)) {
			return $found;
		}
	}

	
}
