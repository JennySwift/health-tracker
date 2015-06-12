<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;

use Auth;

use App\Models\Exercises\Workouts\Series as WorkoutSeries;

class Series extends Model {

    use OwnedByUser;

    protected $fillable = ['name'];

    protected $table = "exercise_series";

    protected $appends = ['path'];

    /**
     * Define relationships
     */
  
    public function user()
    {
        return $this->belongsTo('App\User');
    }
  
    public function workouts()
    {
        return $this->belongsToMany('App\Models\Exercises\Workout');
    }

    public function exercises()
    {
        return $this->hasMany('App\Models\Exercises\Exercise');
    }

    public function entries()
    {
        return $this->hasManyThrough('App\Models\Exercises\Entry','App\Models\Exercises\Exercise');
    }

    /**
     * Appends
     */

    /**
     * Return the URL of the project
     * it needs to be called getFieldAttribute
     * @return string
     */
    public function getPathAttribute()
    {
        return route('ExerciseSeries.destroy', $this->id); // http://tracker.dev:8000/ExerciseSeries/1
    }

    /**
     * select
     */
    
    /**
     * Get all the exercise series that belong to the user
     * @return [type] [description]
     */
    public static function getExerciseSeries () {
        // $user = User::find(Auth::user()->id);
        // $series = $user->exerciseSeries()->orderBy('name', 'asc')->get();

        $series = static::forCurrentUser('exercise_series')->orderBy('name', 'asc')->get();

        //for each series, add to it the workouts the series is in
        // foreach ($exercise_series as $series) {
        //   $series_id = $series->id;

        //   $workouts = $series->workouts;
          
        //   foreach ($workouts as $workout) {
        //      $workout_id = $workout->id;
        //      $workout->contents = $workout->series()->select('exercise_series.id', 'name')->orderBy('name', 'asc')->get();
        //   }

        //   $series->workouts = $workouts;
        // }

        return $series;
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
