<?php namespace App\Models\Exercises\Workouts;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Exercises\Series as ExerciseSeries;

class Series extends Model {

	protected $table = 'series_workout';

	public static function insertSeriesIntoWorkout($workout_id, $series_id) {
		static
			::insert([
				'workout_id' => $workout_id,
				'series_id' => $series_id,
				'user_id' => Auth::user()->id
			]);
	}

	public static function getSeriesWorkouts ($series_id) {
		//get all the workouts an exercise series is in
		$exercise_series = ExerciseSeries::find($series_id);
		$workouts = $exercise_series->workouts()->select('workouts.name', 'workouts.id')->get();

		foreach ($workouts as $workout) {
			$workout_id = $workout->id;
			// $workout->contents = static::getWorkoutContents($workout_id);
			$workout->contents = $workout->series()->select('exercise_series.id', 'name')->orderBy('name', 'asc')->get();
		}

		return $workouts;
	}

	// public static function getWorkoutContents ($workout_id) {
	// 	$workout_contents = static
	// 		::where('workout_id', $workout_id)
	// 		->join('exercise_series', 'series_id', '=', 'exercise_series.id')
	// 		->select('series_id', 'exercise_series.name')
	// 		->orderBy('exercise_series.name', 'asc')
	// 		->get();

	// 	return $workout_contents;	
	// }
}
