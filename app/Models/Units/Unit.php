<?php namespace App\Models\Units;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Foods\Calories;

class Unit extends Model {

	/**
	 * Define relationships
	 */

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function foods()
	{
		return $this->belongsToMany('App\Models\Foods\Food');
	}

	/**
	 * select
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
	 * insert
	 */
	
	/**
	 * Insert a new food unit if it doesn't exist for the user
	 * @param  [type] $unit_name [description]
	 * @return [type]            [description]
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

	/**
	 * update
	 */
	
	/**
	 * delete
	 */
	
}
