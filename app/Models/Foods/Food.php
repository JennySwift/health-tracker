<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use App\Models\Foods\Calories;
use Auth;
use App\User;

class Food extends Model {

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

	/**
	 * select
	 */
	
	public static function autocompleteMenu ($typing) {
		$typing = '%' . $typing . '%';
		
		$menu = DB::select("select * from (select id, name, 'food' as type from foods where name LIKE '$typing' and user_id = " . Auth::user()->id . " UNION select id, name, 'recipe' as type from recipes where name LIKE '$typing' and user_id = " . Auth::user()->id . ") as table1 order by table1.name asc");

		return $menu;
	}

	public static function getFoods () {
		//get all foods for the user
		/**
		 * Method one. This works.
		 */

		// $foods = static
		// 	::where('user_id', Auth::user()->id)
		// 	->orderBy('name', 'asc')
		// 	->get();

		/**
		 * Method two. Refactor attempt.
		 * This works too. Is it better than method one?
		 */
		
		$user = User::find(Auth::user()->id);
		$foods = $user->foods()
			->join('units', 'foods.id', '=', 'units.id')
			->select('foods.id', 'foods.name', 'units.name as default_unit_name', 'units.id as default_unit_id')
			->orderBy('foods.name', 'asc')
			->get();

		return $foods;
	}

	public static function getAllFoodsWithUnits () {
		$foods = static::getFoods();
		$all_foods_with_units = array();

		foreach ($foods as $food) {
			$food_id = $food['id'];
			$food_name = $food['name'];
			$default_unit_id = $food['default_unit_id'];
			$default_unit_name = $food['default_unit_name'];

		    $food = array(
				"id" => $food_id,
				"name" => $food_name,
				"default_unit_name" => $default_unit_name
		    );

		    $default_unit_calories = Calories
		    	::where('food_id', $food_id)
		    	->where('unit_id', $default_unit_id)
		    	->pluck('calories');
		    	
		    $food['default_unit_calories'] = $default_unit_calories;

			$rows = Calories
				::join('foods', 'food_id', '=', 'foods.id')
				->join('units', 'calories.unit_id', '=', 'units.id')
				->where('food_id', $food_id)
				->select('units.name', 'units.id', 'calories', 'default_unit')
				->get();

			$units = array();
			foreach ($rows as $row) {
				$unit_name = $row->name;
				$unit_id = $row->id;
				$calories = $row->calories;
				$default_unit = $row->default_unit;

				if ($default_unit === 1) {
					$default_unit = true;
					$default_unit_id = $unit_id;
					// $default_unit_name = $unit_name;
					// $default_unit_calories = $calories;

					$food['default_unit_id'] = $default_unit_id;
					// $food['default_unit_name'] = $default_unit_name;
					// $food['default_unit_calories'] = $default_unit_calories;
				}
				else {
					$default_unit = false;
				}

				$units[] = array(
					"id" => $unit_id,
					"name" => $unit_name,
					"calories" => $calories,
					"default_unit" => $default_unit
				);
			}

		    $all_foods_with_units[] = array(
		    	"food" => $food,
		    	"units" => $units
		    );
		}
	    
		return $all_foods_with_units;
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
