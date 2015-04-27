<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Foods\Calories;

class FoodUnit extends Model {

	public static function getFoodUnits () {
	    $food_units = static
	    	::where('user_id', Auth::user()->id)
	    	->select('id', 'name')
	    	->orderBy('name', 'asc')
	    	->get();

	    return $food_units;
	}

	public static function getAssocUnits ($food_id) {
		$rows = Calories
			::where('food_id', $food_id)
			->join('foods', 'food_id', '=', 'foods.id')
			->join('food_units', 'calories.unit_id', '=', 'food_units.id')
			->select('food_units.name', 'food_units.id', 'calories', 'default_unit')
			->get();
	   

		$assoc_units = array();
		foreach ($rows as $row) {
			$unit_name = $row->name;
			$unit_id = $row->id;
			$calories = $row->calories;
			$default_unit = $row->default_unit;

			if ($default_unit === 1) {
				$default_unit = true;
			}
			else {
				$default_unit = false;
			}

			$assoc_units[] = array(
				"name" => $unit_name,
				"id" => $unit_id,
				"calories" => $calories,
				"default" => $default_unit
			);
		}
	    
		return $assoc_units;
	}

}
