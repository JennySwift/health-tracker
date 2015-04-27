<?php namespace App\Models\Exercises\Workouts;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Series extends Model {

	protected $table = 'series_workout';

	public static function insertSeriesIntoWorkout($workout_id, $series_id) {
		Series_workout
			::insert([
				'workout_id' => $workout_id,
				'series_id' => $series_id,
				'user_id' => Auth::user()->id
			]);
	}

	public static function getSeriesWorkouts ($series_id) {
		//find which workouts the series is part of
		$workouts = Series_workout
			::where('series_id', $series_id)
			->join('workouts', 'workout_id', '=', 'workouts.id')
			->select('workouts.name', 'workouts.id')
			->get();

		foreach ($workouts as $workout) {
			$workout_id = $workout->id;
			$workout->contents = Series_workout::getWorkoutContents($workout_id);
		}

		return $workouts;
	}

	public static function getWorkoutContents ($workout_id) {
		$workout_contents = Series_workout
			::where('workout_id', $workout_id)
			->join('exercise_series', 'series_id', '=', 'exercise_series.id')
			->select('series_id', 'exercise_series.name')
			->orderBy('exercise_series.name', 'asc')
			->get();

		return $workout_contents;	
	}
}
