<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use App\Models\Foods\Calories;
use Auth;

class Food extends Model {

	protected $fillable = ['name', 'user_id'];

	public static function insertFood ($name) {
		/**
		 * This works.
		 * Any problem with it?
		 * What's the point of the create and save methods if this insert method works?
		 */
		
		static::insert([
			'name' => $name,
			'user_id' => Auth::user()->id
		]);

		/**
		 * First failed attempt
		 * Error: Cannot add or update a child row: a foreign key constraint fails
		 * Also, why do I need to define the mass-assignable columns when I'm only inserting one row?
		 */
		
		// $food = new Food(['name' => $name, 'user_id', Auth::user()->id]);

		/**
		 * Second failed attempt
		 * Error: exception 'BadMethodCallException' with message 'Call to undefined method Illuminate\Database\Query\Builder::user()		
		 */
		
		// $food = new Food(['name' => $name]);
		// $food->user()->associate(Auth::user());				
		// $food->save();
	}

	public static function getFoods () {
		$query = static
			::where('user_id', Auth::user()->id)
			->orderBy('name', 'asc')->get();

		$foods = array();
		foreach ($query as $food) {
			$food_id = $food->id;
			$food_name = $food->name;
			
			$foods[] = array(
				"id" => $food_id,
				"name" => $food_name
			);
		}

		return $foods;
	}

	public static function getAllFoodsWithUnits () {
		$foods = static::getFoods();
		$all_foods_with_units = array();

		foreach ($foods as $food) {
			$food_id = $food['id'];
			$food_name = $food['name'];

		    $food = array(
				"id" => $food_id,
				"name" => $food_name
		    );

			$rows = Calories
				::join('foods', 'food_id', '=', 'foods.id')
				->join('food_units', 'calories.unit_id', '=', 'food_units.id')
				->where('food_id', $food_id)
				->select('food_units.name', 'food_units.id', 'calories', 'default_unit')
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
					$default_unit_name = $unit_name;
					$default_unit_calories = $calories;

					$food['default_unit_id'] = $default_unit_id;
					$food['default_unit_name'] = $default_unit_name;
					$food['default_unit_calories'] = $default_unit_calories;
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

}
