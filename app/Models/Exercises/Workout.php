<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Exercises\Workouts\Series as WorkoutSeries;

class Workout extends Model {

	/**
	 * Define relationships
	 */

	public function user () {
		return $this->belongsTo('App\User');
	}

	public function series() {
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
	
	/**
	 * pivot table
	 */
	
	/**
	 * select
	 */

	// public static function getWorkoutContents ($workout_id) {
	// 	$workout_contents = static
	// 		::where('workout_id', $workout_id)
	// 		->join('exercise_series', 'series_id', '=', 'exercise_series.id')
	// 		->select('series_id', 'exercise_series.name')
	// 		->orderBy('exercise_series.name', 'asc')
	// 		->get();

	// 	return $workout_contents;	
	// }

	/**
	 * insert
	 */
	
	public static function insertSeriesIntoWorkout($workout_id, $series_id) {
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
