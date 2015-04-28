<?php namespace App\Models\Exercises;

use Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exercises\Workouts\Series as WorkoutSeries;

class Series extends Model {

    protected $table = "exercise_series";
  
    public function user () {
        return $this->belongsTo('App\User');
    }
  
    public function workouts () {
        return $this->belongsToMany('App\Models\Exercises\Workout');
    }

    public function exercises () {
        return $this->hasMany('App\Models\Exercises\Exercise');
    }

    public function entries () {
        return $this->hasManyThrough('App\Models\Exercises\Entry','App\Models\Exercises\Exercise');
    }

    public static function getExerciseSeries () {
        // $series = Series::find(1);

        $exercise_series = static
        ::where('user_id', Auth::user()->id)
        ->select('name', 'id')
        ->orderBy('name', 'asc')
        ->get();

        foreach ($exercise_series as $series) {
          $series_id = $series->id;
          $series->workouts = WorkoutSeries::getSeriesWorkouts($series_id);
        }

        return $exercise_series;
        // $series->first()->workouts => Collection of workouts connected to this series
    }

}
