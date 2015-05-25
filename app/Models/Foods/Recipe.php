<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Debugbar;

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
			->with('tags')
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
			->select('foods.id as food_id', 'foods.name', 'units.name as unit_name', 'units.id as unit_id', 'quantity', 'description')
			->get();

		//Add the units to all the foods in $contents, for the temporary recipe popup
		$contents_with_units = [];
		foreach ($contents as $ingredient) {
			$food_id = $ingredient->food_id;
			$food = Food::find($food_id);
			$ingredient->units = $food->units;
			$contents_with_units[] = $ingredient;
		}
		
		return [
			'recipe' => $recipe,
			'contents' => $contents_with_units,
			'steps' => $recipe->steps,
			'tags' => $recipe->tags->lists('id')
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

	public static function insertTagsIntoRecipe($recipe, $tag_ids)
	{
		foreach ($tag_ids as $tag_id) {
			static::insertTagIntoRecipe($recipe, $tag_id);
		}
	}

	/**
	 * Inserts a tag into the taggables table for a recipe
	 * @param  [type] $recipe_id [description]
	 * @param  [type] $tag_id    [description]
	 * @return [type]            [description]
	 */
	public static function insertTagIntoRecipe($recipe, $tag_id)
	{
		$recipe->tags()->attach($tag_id, ['taggable_type' => 'recipe']);
	}

	/**
	 * This probably needs doing after refactor
	 * @param  [type] $recipe_id [description]
	 * @param  [type] $data      [description]
	 * @return [type]            [description]
	 */
	public static function insertFoodIntoRecipe($recipe, $data)
	{
		if (isset($data['description'])) {
			$description = $data['description'];
		}
		else {
			$description = null;
		}

		Debugbar::info('recipeid: ' . $recipe->id);
		Debugbar::info('data', $data);

		DB::table('food_recipe')
			->insert([
				'recipe_id' => $recipe->id,
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
	
}
