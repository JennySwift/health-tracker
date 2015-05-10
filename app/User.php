<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Auth;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * Define relationships
	 * I didn't put tags here because the database schema may be changing for the tags.
	 */
	
	//tags
	public function recipeTags () {
		return $this->hasMany('App\Models\Tags\Tag')->where('for', 'recipe');
	}

	public function exerciseTags () {
		return $this->hasMany('App\Models\Tags\Tag')->where('for', 'exercise');
	}

	//exercises
	public function exercises () {
		return $this->hasMany('App\Models\Exercises\Exercise');
	}

	public function exerciseEntries () {
		return $this->hasMany('App\Models\Exercises\Entry');
	}

	public function exerciseSeries () {
		return $this->hasMany('App\Models\Exercises\Series');
	}

	public function exerciseUnits () {
		return $this->hasMany('App\Models\Units\Unit')->where('for', 'exercise');
	}

	public function workouts () {
		return $this->hasMany('App\Models\Exercises\Workout');
	}

	//foods
	public function foods () {
		return $this->hasMany('App\Models\Foods\Food');
	}

	public function foodEntries () {
		return $this->hasMany('App\Models\Foods\Entry');
	}

	public function foodUnits () {
		return $this->hasMany('App\Models\Units\Unit')->where('for', 'food');
	}

	public function recipes () {
		return $this->hasMany('App\Models\Foods\Recipe');
	}

	//weight
	public function weights () {
		return $this->hasMany('App\Models\Weights\Weight');
	}

	//journal
	public function journal () {
		return $this->hasMany('App\Models\Journal\Journal');
	}

	/**
	 * End of defining relationships
	 */

	public static function getAllFoodsWithUnits () {
		$user = User::find(Auth::user()->id);
		$foods = $user->foods;

		$array = [];
		foreach ($foods as $food) {
			$units = $food->units;
			$food->units = $units;
			$array[] = $food;
		}
		return $array;
	}

	/**
	 * Get all the user's workouts
	 * @return [type] [description]
	 */
	public static function getWorkouts () {
		$user = User::find(Auth::user()->id);
		$workouts = $user->workouts;

		//get all the series that are in each workout
		foreach ($workouts as $workout) {
			$workout->contents = $workout->series()->select('exercise_series.id', 'name')->orderBy('name', 'asc')->get();
		}

		return $workouts;
	}

	/**
	 * Get all the exercise series that belong to the user
	 * @return [type] [description]
	 */
	public static function getExerciseSeries () {
		$user = User::find(Auth::user()->id);
		$series = $user->exerciseSeries()->orderBy('name', 'asc')->get();

	    //for each series, add to it the workouts the series is in
	    // foreach ($exercise_series as $series) {
	    //   $series_id = $series->id;

	    //   $workouts = $series->workouts;
	      
	    //   foreach ($workouts as $workout) {
	    //   	$workout_id = $workout->id;
	    //   	$workout->contents = $workout->series()->select('exercise_series.id', 'name')->orderBy('name', 'asc')->get();
	    //   }

	    //   $series->workouts = $workouts;
	    // }

	    return $series;
	}
	
}
