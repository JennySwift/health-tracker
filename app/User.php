<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

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
		return $this->hasMany('App\Models\Exercises\Unit');
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
		return $this->hasMany('App\Models\Foods\Unit');
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
	
}
