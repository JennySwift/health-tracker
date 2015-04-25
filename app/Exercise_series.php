<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Series_workout;

class Exercise_series extends Model {

	public static function getExerciseSeries () {
		$exercise_series = Exercise_series
			::where('user_id', Auth::user()->id)
			->select('name', 'id')
			->orderBy('name', 'asc')
			->get();

		foreach ($exercise_series as $series) {
			$series_id = $series->id;
			$series->workouts = Series_workout::getSeriesWorkouts($series_id);
		}

		return $exercise_series;
	}

}
