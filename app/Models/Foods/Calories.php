<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Foods\FoodEntry;

class Calories extends Model {

	public static function updateCalories ($food_id, $unit_id, $calories) {
		Calories
			::where('food_id', $food_id)
			->where('unit_id', $unit_id)
			->update([
				'calories' => $calories
			]);
	}

	public static function insertUnitInCalories ($food_id, $unit_id) {
		Calories
			::insert([
				'food_id' => $food_id,
				'unit_id' => $unit_id,
				'user_id' => Auth::user()->id
			]);
	}

	public static function deleteUnitFromCalories ($food_id, $unit_id) {
		Calories
			::where('food_id', $food_id)
			->where('unit_id', $unit_id)
			->delete();
	}

	public static function updateDefaultUnit ($food_id, $unit_id) {
		Calories
			::where('food_id', $food_id)
			->where('default_unit', 1)
			->update([
				'default_unit' => 0
			]);

		Calories
			::where('food_id', $food_id)
			->where('unit_id', $unit_id)
			->update([
				'default_unit' => 1
			]);	
	}

	public static function getDefaultUnit ($food_id) {
		$unit_id = Calories
			::where('default_unit', 1)
			->where('food_id', $food_id)
			->where('user_id', Auth::user()->id)
			->pluck('unit_id');
		
		return $unit_id;
	}

	public static function getCalories ($food_id, $unit_id) {
		$calories = Calories
			::where('food_id', $food_id)
			->where('unit_id', $unit_id)
			->pluck('calories');
		
		return $calories;
	}

	public static function getCaloriesForQuantity ($calories_for_item, $quantity) {
		$calories_for_quantity = $calories_for_item * $quantity;
		return $calories_for_quantity;
	}

	public static function getCaloriesForTimePeriod ($date, $period) {
		$calories_for_period = 0;

		if ($period === "day") {
			$rows = FoodEntry
				::join('foods', 'food_entries.food_id', '=', 'foods.id')
				->join('food_units', 'food_entries.unit_id', '=', 'food_units.id')
				->where('date', $date)
				->where('food_entries.user_id', Auth::user()->id)
				->select('food_id', 'food_units.id AS unit_id', 'quantity')
				->get();
		}
		elseif ($period === "week") {
			$a_week_ago = getDaysAgo($date);
			$rows = FoodEntry
				::join('foods', 'food_entries.food_id', '=', 'foods.id')
				->join('food_units', 'food_entries.unit_id', '=', 'food_units.id')
				->where('date', '>=', $a_week_ago)
				->where('date', '<=', $date)
				->where('food_entries.user_id', Auth::user()->id)
				->select('food_id', 'food_units.id AS unit_id', 'quantity')
				->get();
		}

		foreach ($rows as $row) {
			$food_id = $row->food_id;
			$unit_id = $row->unit_id;
			$quantity = $row->quantity;

			$calories_for_item = getCalories($food_id, $unit_id);
			$calories_for_quantity = getCaloriesForQuantity($calories_for_item, $quantity);
			$calories_for_period += $calories_for_quantity;
		}

		if ($period === "week") {
			$calories_for_period /= 7;
		}
		return $calories_for_period;
	}

}
