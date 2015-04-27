<?php namespace App\Models\Exercises;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Series extends Model {

  protected $table = "exercise_series";
  
  /*
      $series = Series::find(1);
      $series->user->name;
  */
  public function user(){
      return $this->belongsTo('App\User');
  }
  
  public function workouts(){
     return $this->belongsToMany('App\Models\Exercises\Workout');
  }

  public function exercises() {
  	return $this->hasMany('App\Models\Exercises\Exercise');
  }

  public function entries()
    {
        return $this->hasManyThrough(
          'App\Models\Exercises\Entry', 
          'App\Models\Exercises\Exercise'
        );
    }

	public static function getExerciseSeries () {
	  
	  // 1st option
	  /*$series = new static;
		$series->where('user_id', Auth::user()->id)
			->select('name', 'id')
			->orderBy('name', 'asc')
			->get();*/
			
		// 2nd option (best approach)
		$series = static::where('user_id', Auth::user()->id)
              			->select('name', 'id')
              			->orderBy('name', 'asc')
              			->get();

		return $series;
		
		// $series->first()->workouts => Collection of workouts connected to this series
	}

}

// use Illuminate\Database\Eloquent\Model;
// use Auth;
// use App\Models\Exercises\Workouts\Series as WorkoutSeries;

// class Series extends Model {

// 	public static function getExerciseSeries () {
// 		$exercise_series = Exercise_series
// 			::where('user_id', Auth::user()->id)
// 			->select('name', 'id')
// 			->orderBy('name', 'asc')
// 			->get();

// 		foreach ($exercise_series as $series) {
// 			$series_id = $series->id;
// 			$series->workouts = WorkoutSeries::getSeriesWorkouts($series_id);
// 		}

// 		return $exercise_series;
// 	}

// }
