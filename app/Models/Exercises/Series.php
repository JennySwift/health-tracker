<?php namespace App\Models\Exercises;

use Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exercises\Workouts\Series as WorkoutSeries;

class Series extends Model {

    protected $table = "exercise_series";

    /**
     * Define relationships
     */
  
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

    /**
     * select
     */
    
    public static function getExerciseSeries () {
        //get all the series belonging to the user
        $exercise_series = static
        ::where('user_id', Auth::user()->id)
        ->select('name', 'id')
        ->orderBy('name', 'asc')
        ->get();

        //for each series, add to it the workouts the series is in
        foreach ($exercise_series as $series) {
          $series_id = $series->id;
          $series->workouts = WorkoutSeries::getSeriesWorkouts($series_id);
        }

        return $exercise_series;
    }
    
    /**
     * insert
     */
    
    /**
     * update
     */
    
    /**
     * delete
     */

}
