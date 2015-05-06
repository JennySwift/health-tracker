<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Foods\FoodEntry;
use Carbon\Carbon;

class Calories extends Model {

	/**
	 * Define relationships
	 */

	/**
	 * select
	 */
	
	public static function getDefaultUnit ($food_id) {
		$unit_id = static
			::where('default_unit', 1)
			->where('food_id', $food_id)
			->where('user_id', Auth::user()->id)
			->pluck('unit_id');
		
		return $unit_id;
	}

	public static function getCalories ($food_id, $unit_id) {
		$calories = static
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
				->join('units', 'food_entries.unit_id', '=', 'units.id')
				->where('date', $date)
				->where('food_entries.user_id', Auth::user()->id)
				->select('food_id', 'units.id AS unit_id', 'quantity')
				->get();
		}
		elseif ($period === "week") {
			// $a_week_ago = getDaysAgo($date);
			$a_week_ago = Carbon::createFromFormat('Y-m-d', $date)->subWeek(1)->format('Y-m-d');
			$rows = FoodEntry
				::join('foods', 'food_entries.food_id', '=', 'foods.id')
				->join('units', 'food_entries.unit_id', '=', 'units.id')
				->where('date', '>=', $a_week_ago)
				->where('date', '<=', $date)
				->where('food_entries.user_id', Auth::user()->id)
				->select('food_id', 'units.id AS unit_id', 'quantity')
				->get();
		}

		foreach ($rows as $row) {
			$food_id = $row->food_id;
			$unit_id = $row->unit_id;
			$quantity = $row->quantity;

			$calories_for_item = static::getCalories($food_id, $unit_id);
			$calories_for_quantity = static::getCaloriesForQuantity($calories_for_item, $quantity);
			$calories_for_period += $calories_for_quantity;
		}

		if ($period === "week") {
			$calories_for_period /= 7;
		}
		return $calories_for_period;
	}

	
	/**
	 * insert
	 */
	
	public static function insertUnitInCalories ($food_id, $unit_id) {
		static
			::insert([
				'food_id' => $food_id,
				'unit_id' => $unit_id,
				'user_id' => Auth::user()->id
			]);
	}

	/**
	 * update
	 */
	
	public static function updateCalories ($food_id, $unit_id, $calories) {
		static
			::where('food_id', $food_id)
			->where('unit_id', $unit_id)
			->update([
				'calories' => $calories
			]);
	}

	public static function updateDefaultUnit ($food_id, $unit_id) {
		static
			::where('food_id', $food_id)
			->where('default_unit', 1)
			->update([
				'default_unit' => 0
			]);

		static
			::where('food_id', $food_id)
			->where('unit_id', $unit_id)
			->update([
				'default_unit' => 1
			]);	
	}

	/**
	 * delete
	 */

	public static function deleteUnitFromCalories ($food_id, $unit_id) {
		static
			::where('food_id', $food_id)
			->where('unit_id', $unit_id)
			->delete();
	}
}
