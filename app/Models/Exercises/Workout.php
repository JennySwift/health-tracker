<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Workout extends Model {

  public function series() {
      return $this->belongsToMany('App\Models\Exercises\Series');
  }

	public static function getWorkouts () {
		$workouts = static
			::where('user_id', Auth::user()->id)
			->select('id', 'name')
			->get();

		//get all the series that are in the workout
		/*foreach ($workouts as $workout) {
			$workout_id = $workout->id;
			$workout->contents = Series_workout::getWorkoutContents($workout_id);
		}*/
		

		return $workouts;
		// $workouts->first()->series->user->name
	}

}

// use Illuminate\Database\Eloquent\Model;
// use Auth;

// class Workout extends Model {

// 	public static function getWorkouts () {
// 		$workouts = static
// 			::where('user_id', Auth::user()->id)
// 			->select('id', 'name')
// 			->get();

// 		//get all the series that are in the workout
// 		foreach ($workouts as $workout) {
// 			$workout_id = $workout->id;
// 			$workout->contents = Series_workout::getWorkoutContents($workout_id);
// 		}

// 		return $workouts;
// 	}

// }
