<?php namespace App\Models\Exercises;

use Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exercises\Workouts\Series as WorkoutSeries;

class Series extends Model {

    protected $fillable = ['name'];

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
