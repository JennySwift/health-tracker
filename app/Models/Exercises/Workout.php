<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;
use Auth;
use App\Models\Exercises\Workouts\Series as WorkoutSeries;

/**
 * Class Workout
 * @package App\Models\Exercises
 */
class Workout extends Model {

	use OwnedByUser;

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
	{
		return $this->belongsTo('App\User');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function series()
	{
	  return $this->belongsToMany('App\Models\Exercises\Series');
	}

    /**
     * Get all the user's workouts with the contents of each workout
     * @return mixed
     */
	public static function getWorkouts () {
		$workouts = static::forCurrentUser()->get();

		//get all the series that are in each workout
		foreach ($workouts as $workout) {
			$workout->contents = $workout->series()->select('exercise_series.id', 'name')->orderBy('name', 'asc')->get();
		}

		return $workouts;
	}
	
}
