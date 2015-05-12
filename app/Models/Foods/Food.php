<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;
use App\Models\Foods\Calories;
use App\Models\Units\Unit;
use Auth;
use App\User;
use DB;
use Carbon\Carbon;

class Food extends Model {

	use OwnedByUser;

	protected $fillable = ['name'];

	/**
	 * Define relationships
	 */

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function entries()
	{
		return $this->hasMany('App\Models\Foods\Entry');
	}

	public function recipes()
	{
		return $this->belongsToMany('App\Models\Foods\Recipe', 'food_recipe', 'food_id', 'recipe_id');
	}

	public function units()
	{
		return $this->belongsToMany('App\Models\Units\Unit');
	}

	/**
	 * select
	 */
	
	/**
	 * Get all food units that belong to the user,
	 * as well as all units that belong to the particular food.
	 * 
	 * For when user clicks on a food in the foods table
	 * A popup is displayed, showing all food units
	 * with the units for that food checked
	 * and the option to set the default unit for the food
	 * and the option to set the calories for each of the food's units
	 */
	public static function getFoodInfo($food)
	{
		dd($food);
		$user = User::find(Auth::user()->id);
		$all_food_units = $user->foodUnits;
		$food_units = $food->units()->lists('unit_id');

		return [
			"all_food_units" => $all_food_units,
			"food" => $food,
			"food_units" => $food_units
		];
	}
	
	/**
	 * Get all the user's foods, with the name of each food's default unit
	 */
	public static function getFoods()
	{ 
		/**
		 * @VP:
		 * When I use this code I get the following error.
		 * Column 'user_id' in where clause is ambiguous
		 * (SQL: select `foods`.`id`, `foods`.`name`, `units`.`name` as `default_unit_name`, `units`.`id` as `default_unit_id` from `foods` left join `units` on `foods`.`default_unit_id` = `units`.`id` where `user_id` = 1 order by `foods`.`name` asc)
         * @JS:
         * This one is easy: that is only because in your where clause, you are trying to do "user_id=1", but you are
         * joining two tables together and both have a user_id field, so you need to be more specific on which
         * user_id you want: foods.user_id or units.user_id ??
		 */
		/**
		 * @VP:
		 * I get that, but in case it was unclear I was referring to the code down below
		 * that is now on line 107. So I don't know how to change your forCurrentUser method to use the correct user_id.
         * @JS:
         * The method forCurrentUser() has no argument (try to go in the
         * App\Traits\Models\Relationships\OwnedByUser file and see for yourself) this will automatically use the
         * logged user for you (that is why I called the method forCurrentUser) so no need to fetch the user before
         * or anything, just use the scope like you did line 112.
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

	public static function getCalories($food_id, $unit_id)
	{
		$food = static::find($food_id);
		$calories = $food->units()
			->where('unit_id', $unit_id)
			->pluck('calories');

		return $calories;
	}

	public static function getCaloriesForQuantity($calories_for_item, $quantity)
	{
		$calories_for_quantity = $calories_for_item * $quantity;
		return $calories_for_quantity;
	}

	public static function getCaloriesForTimePeriod($date, $period)
	{
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

			$calories_for_item = Food::getCalories($food_id, $unit_id);
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
	
	public static function insertFood($name)
	{
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

	public static function insertFoodIfNotExists($food_name)
	{
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

	public static function insertUnitInCalories($food, $unit_id)
	{
		$food->units()->attach($unit_id, ['user_id' => Auth::user()->id]);
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */

}
