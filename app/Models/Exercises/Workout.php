<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;
use Auth;
use App\Models\Exercises\Workouts\Series as WorkoutSeries;

class Workout extends Model {

	use OwnedByUser;
	
    protected $fillable = ['name'];

	/**
	 * Define relationships
	 */

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function series()
	{
	  return $this->belongsToMany('App\Models\Exercises\Series');
	}

	/**
	 * select
	 */
	
	/**
	 * Get all the user's workouts with the contents of each workout
	 * @return [type] [description]
	 */
	public static function getWorkouts () {
		// $user = User::find(Auth::user()->id);
		// $workouts = $user->workouts;

		$workouts = static::forCurrentUser()->get();

		//get all the series that are in each workout
		foreach ($workouts as $workout) {
			$workout->contents = $workout->series()->select('exercise_series.id', 'name')->orderBy('name', 'asc')->get();
		}

		return $workouts;
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
