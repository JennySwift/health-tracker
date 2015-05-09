<?php namespace App\Models\Exercises\Workouts;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Exercises\Series as ExerciseSeries;

/**
 * pivot table model
 */

class Series extends Model {

	protected $table = 'series_workout';

	/**
	 * Define relationships
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
