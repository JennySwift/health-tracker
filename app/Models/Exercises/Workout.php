<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Exercises\Workouts\Series as WorkoutSeries;

class Workout extends Model {

    protected $fillable = ['name'];

	/**
	 * Define relationships
	 */

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function series()
	{
	  return $this->belongsToMany('App\Models\Exercises\Series');
	}

	/**
	 * select
	 */
	
	/**
	 * insert
	 */
	
	/**
	 * update
	 */
	
	/**
	 * delete
	 */
	
}
