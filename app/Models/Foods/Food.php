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
	 * For when user clicks on a food in the foods table
	 * A popup is displayed, showing all food units
	 * with the units for that food checked
	 * and the option to set the default unit for the food
	 * and the option to set the calories for each of the food's units
	 *
	 * Needs fixing after refactor
	 */
	public static function getFoodInfo ($food_id) {
		$food_units = Unit::getFoodUnits();

		//Get all units that belong to the food
		$food = Food::find($food_id);
		$assoc_units = $food->units()
			->select('units.name', 'units.id', 'calories')
			->get();

		dd($assoc_units);
		$units = array();
		$default_unit_id = Food
			::where('id', $food_id)
			->pluck('default_unit_id');

		//checking to see if the unit has already been given to a food, so that it appears checked.
		foreach ($food_units as $food_unit) {
			$unit_id = $food_unit->id;
			$unit_name = $food_unit->name;
			$match = 0;

			foreach ($assoc_units as $assoc_unit) {
				$assoc_unit_id = $assoc_unit['id'];
				$calories = $assoc_unit['calories'];

				if ($unit_id == $assoc_unit_id) {
					$match++;
				}
			}
			if ($match === 1) {
				$calories = Calories::getCalories($food_id, $unit_id);

				$units[] = array(
					"id" => $unit_id,
					"name" => $unit_name,
					"checked" => true,
					"calories" => $calories
				);
			}
			else {
				$units[] = array(
					"id" => $unit_id,
					"name" => $unit_name,
					"checked" => false,
				);
			}
		}

		/**
		 * Why, oh why, is id in my javascript response null here, when Postman has the correct value?
		 * And Postman has the correct "checked" values, whereas in my JS respone they are all false.
		 */

		$food = array(
			"id" => $food_id,
			"default_unit_id" => $default_unit_id
		);



			
		return [
			"food" => $food,
			"units" => $units
		];
	}

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

	public static function getAllFoodsWithUnits () {
		// $foods = static::getFoods();

		// // dd($foods);
		// $all_foods_with_units = array();

		// foreach ($foods as $food) {
		// 	$food_id = $food['id'];
		// 	$food_name = $food['name'];
		// 	$default_unit_id = $food['default_unit_id'];
		// 	$default_unit_name = $food['default_unit_name'];

		// 	//populate the food object
		//     $food = array(
		// 		"id" => $food_id,
		// 		"name" => $food_name,
		// 		"default_unit_id" => $default_unit_id,
		// 		"default_unit_name" => $default_unit_name
		//     );

		//     $default_unit_calories = Calories
		//     	::where('food_id', $food_id)
		//     	->where('unit_id', $default_unit_id)
		//     	->pluck('calories');
		    	
		//     $food['default_unit_calories'] = $default_unit_calories;

		//     //Populate $units
		//     //Each unit is an object with id, name and calories
		// 	$units = Calories
		// 		::join('foods', 'food_id', '=', 'foods.id')
		// 		->join('units', 'calories.unit_id', '=', 'units.id')
		// 		->where('food_id', $food_id)
		// 		->select('units.name', 'units.id', 'calories')
		// 		->get();

		// 	// dd($rows);

		//     $all_foods_with_units[] = array(
		//     	"food" => $food,
		//     	"units" => $units
		//     );
		// }
	    
		// return $all_foods_with_units;
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

}
