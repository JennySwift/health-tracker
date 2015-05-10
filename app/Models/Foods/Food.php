<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;
use App\Models\Foods\Calories;
use App\Models\Units\Unit;
use Auth;
use App\User;

class Food extends Model {

	/**
	 * @VP:
	 * Why is the following line needed when there is already this at the top of the file:
	 * use App\Traits\Models\Relationships\OwnedByUser;
	 */

	use OwnedByUser;

	protected $fillable = ['name'];

	/**
	 * Define relationships
	 */

	public function user () {
		return $this->belongsTo('App\User');
	}

	public function entries () {
		return $this->hasMany('App\Models\Foods\FoodEntry');
	}

	public function recipes () {
		return $this->belongsToMany('App\Models\Foods\Recipe', 'food_recipe', 'food_id', 'recipe_id');
	}

	public function units () {
		return $this->belongsToMany('App\Models\Units\Unit');
	}

	/**
	 * select
	 */
	
	/**
	 * Get all the user's foods, with the name of each food's default unit
	 */
	public static function getFoods () { 
		/**
		 * @VP:
		 * When I use this code I get the following error.
		 * Column 'user_id' in where clause is ambiguous
		 * (SQL: select `foods`.`id`, `foods`.`name`, `units`.`name` as `default_unit_name`, `units`.`id` as `default_unit_id` from `foods` left join `units` on `foods`.`default_unit_id` = `units`.`id` where `user_id` = 1 order by `foods`.`name` asc)
		 */
		
		/**
		 * @VP:
		 * Also, this is calling the scopeForCurrentUser method in OwnedByUser.php, right?
		 * If I'm right, why is it static::forCurrentUser() and not static::scopeForCurrentUser()?
		 */

		// $foods = static::forCurrentUser()
		// 	->leftJoin('units', 'foods.default_unit_id', '=', 'units.id')
		// 	->select('foods.id', 'foods.name', 'units.name as default_unit_name', 'units.id as default_unit_id')
		// 	->orderBy('foods.name', 'asc')
		// 	->get();

		/**
		 * This is my old code that works
		 */
		 
		$user = User::find(Auth::user()->id);
		$foods = $user->foods()
			->leftJoin('units', 'foods.default_unit_id', '=', 'units.id')
			->select('foods.id', 'foods.name', 'units.name as default_unit_name', 'units.id as default_unit_id')
			->orderBy('foods.name', 'asc')
			->get();

		return $foods;
	}

	/**
	 * insert
	 */
	
	public static function insertFood ($name) {
		// static::insert([
		// 	'name' => $name,
		// 	'user_id' => Auth::user()->id
		// ]);
		// 
		$food = new static(['name' => $name]);
		$food->user()->associate(Auth::user());
		$food->save();

		return $food;
	}

	public static function insertFoodIfNotExists ($food_name) {
		//for quick recipe
		include(app_path() . '/inc/functions.php');
		$count = countItem('foods', $food_name);

		if ($count < 1) {
			//the food does not exist. create the new food.
			$food_id = static
				::insertGetId([
					'name' => $food_name,
					'user_id' => Auth::user()->id
				]);
		}
		else {
			//the food exists. retrieve the id of the food
			$food_id = getId('foods', $food_name);
		}

		return $food_id;
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */
	
	/**
	 * from old FoodRecipe model
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

	public static function deleteFoodFromRecipe ($id) {
		static
			::where('id', $id)
			->delete();
	}

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

}
