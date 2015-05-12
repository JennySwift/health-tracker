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
	 * This needs fixing. It is from my old pivot table model, series_workout.
	 * @param  [type] $workout_id [description]
	 * @param  [type] $series_id  [description]
	 * @return [type]             [description]
	 */
	public static function insertSeriesIntoWorkout($workout_id, $series_id)
	{
		static
			::insert([
				'workout_id' => $workout_id,
				'series_id' => $series_id,
				'user_id' => Auth::user()->id
			]);
	}
	
	/**
	 * update
	 */
	
	/**
	 * delete
	 */
	
}
