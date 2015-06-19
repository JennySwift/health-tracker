<?php namespace App\Models\Units;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

use App\Models\Foods\Calories;

/**
 * Class Unit
 * @package App\Models\Units
 */
class Unit extends Model {

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function foods()
	{
		return $this->belongsToMany('App\Models\Foods\Food');
	}

    /**
     *
     * @return mixed
     */
    public static function getExerciseUnits()
	{
		$units = static
			::where('user_id', Auth::user()->id)
			->where('for', 'exercise')
			->orderBy('name', 'asc')
			->get();

		return $units;
	}

    /**
     *
     * @return mixed
     */
    public static function getFoodUnits()
	{
		$units = static
			::where('user_id', Auth::user()->id)
			->where('for', 'food')
			->orderBy('name', 'asc')
			->get();

		return $units;
	}

    /**
     *
     * @param $food
     * @return mixed
     */
    public static function getFoodUnitsWithCalories($food)
	{
		$units = static
			::where('user_id', Auth::user()->id)
			->where('for', 'food')
			->orderBy('name', 'asc')
			->get();

		//Add the calories for the units that belong to the food
		foreach ($units as $unit) {
			$calories = DB::table('food_unit')
				->where('food_id', $food->id)
				->where('unit_id', $unit->id)
				->pluck('calories');

			$unit->calories = $calories;	
		}

		return $units;
	}

    /**
     *
     * @return array
     */
    public static function getAllUnits()
	{
		$food_units = static::getFoodUnits();
		$exercise_units = static::getExerciseUnits();

		$units = [
			'food' => $food_units,
			'exercise' => $exercise_units
		];

		return $units;
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
     * Insert a new food unit if it doesn't exist for the user
     * @param $unit_name
     * @return mixed
     */
	public static function insertUnitIfNotExists($unit_name)
	{
		//Check if the unit exists
		$count = Unit::where('user_id', Auth()->user('id'))
			->where('name', $unit_name)
			->where('for', 'food')
			->count();

		if ($count < 1) {
			//the unit does not yet exist so we need to create it
			$unit_id = static
				::insertGetId([
					'name' => $unit_name,
					'for' => 'food',
					'user_id' => Auth::user()->id
				]);
		}
		else {
			//the unit exists. retrieve the id of the unit
			$unit_id = static::getId('units', $unit_name);
		}

		return $unit_id;
	}
	
}
