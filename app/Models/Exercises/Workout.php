<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Exercises\Workouts\Series as WorkoutSeries;

class Workout extends Model {

  public function series() {
      return $this->belongsToMany('App\Models\Exercises\Series');
  }

	public static function getWorkouts () {
		//get the workouts
		$workouts = static
			::where('user_id', Auth::user()->id)
			->select('id', 'name')
			->get();

		//get all the series that are in each workout
		foreach ($workouts as $workout) {
			// $workout_id = $workout->id;
			// $workout->contents = WorkoutSeries::getWorkoutContents($workout_id);
			$workout->contents = $workout->series()->select('exercise_series.id', 'name')->orderBy('name', 'asc')->get();
		}

		return $workouts;
	}

}
