<?php namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;
use App\Models\Menu\Entry;
use App\Models\Units\Unit;
use Auth;
use App\User;
use DB;
use Debugbar;
use Carbon\Carbon;

/**
 * Class Food
 * @package App\Models\Menu
 */
class Food extends Model {

	use OwnedByUser;

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @var array
     */
    protected $appends = ['path', 'defaultCalories'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
	{
		return $this->belongsTo('App\User');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entries()
	{
		return $this->hasMany('App\Models\Foods\Entry');
	}

    /**
     * Not sure if the 3rd and 4th parameters should be switched here.
     * I had to switch them in the recipe model.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	public function recipes()
	{
		return $this->belongsToMany('App\Models\Foods\Recipe', 'food_recipe', 'food_id', 'recipe_id');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function units()
	{
		return $this->belongsToMany('App\Models\Units\Unit');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function defaultUnit()
	{
		return $this->hasOne('App\Models\Units\Unit', 'id', 'default_unit_id');
	}

    /**
     *
     * @return mixed
     */
    public function getDefaultCaloriesAttribute()
    {
        return DB::table('food_unit')
            ->where('food_id', $this->id)
            ->where('unit_id', $this->default_unit_id)
            ->pluck('calories');
    }
	
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
		$all_food_units = Unit::getFoodUnitsWithCalories($food);
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
		$foods = static::forCurrentUser('foods')
			->leftJoin('units', 'foods.default_unit_id', '=', 'units.id')
			->select('foods.id', 'foods.name', 'units.name as default_unit_name', 'units.id as default_unit_id')
			->orderBy('foods.name', 'asc')
			->get();

		return $foods;
	}

    /**
     *
     * @param $food_id
     * @param $unit_id
     * @return mixed
     */
    public static function getCalories($food_id, $unit_id)
	{
		$food = static::find($food_id);
		$calories = $food->units()
			->where('unit_id', $unit_id)
			->pluck('calories');

		return $calories;
	}

    /**
     *
     * @param $calories_for_item
     * @param $quantity
     * @return mixed
     */
    public static function getCaloriesForQuantity($calories_for_item, $quantity)
	{
		$calories_for_quantity = $calories_for_item * $quantity;
		return $calories_for_quantity;
	}

    /**
     *
     * @param $date
     * @return int|mixed
     */
    public static function getCaloriesForDay($date)
	{
		$calories_for_day = 0;

		//Get the user's food entries for the date
		$entries = Entry
			::where('date', $date)
			->where('food_entries.user_id', Auth::user()->id)
			->select('food_entries.food_id', 'food_entries.unit_id', 'quantity', 'date')
			->get();

		//Get the calories for the entries
		foreach ($entries as $entry) {
			$calories_for_item = Food::getCalories($entry->food_id, $entry->unit_id);
			$calories_for_quantity = static::getCaloriesForQuantity($calories_for_item, $entry->quantity);
			$calories_for_day += $calories_for_quantity;
		}

		return $calories_for_day;
	}

	/**
	 * Get total calories for 7 days ago, starting from $date.
	 * Return the average/day.
	 * @param  [type] $date [description]
	 * @return [type]       [description]
	 */
	public static function getCaloriesFor7Days($date)
	{
		$total = 0;

		foreach (range(0, 6) as $days) {
			$calories_for_one_day = static::getCaloriesForDay(Carbon::createFromFormat('Y-m-d', $date)->subDays($days)->format('Y-m-d'));
			$total+= $calories_for_one_day;
		}

		return $total / 7;
	}

    /**
     *
     * @param $table
     * @param $name
     * @return mixed
     */
    public static function getId($table, $name)
	{
		$id = DB::table($table)
			->where('name', $name)
			->where('user_id', Auth::user()->id)
			->pluck('id');

		return $id;
	}

    /**
     *
     * @param $table
     * @param $name
     * @return mixed
     */
    public static function countItem($table, $name)
	{
		$count = DB::table($table)
			->where('name', $name)
			->where('user_id', Auth::user()->id)
			->count();

		return $count;
	}

    /**
     *
     * @param $food_name
     * @return mixed
     */
    public static function insertFoodIfNotExists($food_name)
	{
		//for quick recipe
		$count = static::countItem('foods', $food_name);

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
			$food_id = static::getId('foods', $food_name);
		}

		return $food_id;
	}

    /**
     * Return the URL of the project
     * it needs to be called getFieldAttribute
     * @return string
     */
    public function getPathAttribute()
    {
        return route('api.foods.show', $this->id);
    }

}
