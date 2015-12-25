<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Models\Exercises\Entry as ExerciseEntry;
use App\Models\Exercises\Exercise;
use Gravatar;
use Auth;

/**
 * Class User
 * @package App
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $appends = ['gravatar'];

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
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function programs()
    {
        return $this->hasMany('App\Models\Exercises\Program');
    }

    /**
     *
     * @return mixed
     */
    public function recipeTags()
    {
        return $this->hasMany('App\Models\Tags\Tag')->where('for', 'recipe');
    }

    /**
     *
     * @return mixed
     */
    public function exerciseTags()
    {
        return $this->hasMany('App\Models\Tags\Tag')->where('for', 'exercise');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exercises()
    {
        return $this->hasMany('App\Models\Exercises\Exercise');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exerciseEntries()
    {
        return $this->hasMany('App\Models\Exercises\Entry');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exerciseSeries()
    {
        return $this->hasMany('App\Models\Exercises\Series');
    }

    /**
     *
     * @return mixed
     */
    public function exerciseUnits()
    {
        return $this->hasMany('App\Models\Units\Unit')->where('for', 'exercise');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workouts()
    {
        return $this->hasMany('App\Models\Exercises\Workout');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function foods()
    {
        return $this->hasMany('App\Models\Foods\Food');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function foodEntries()
    {
        return $this->hasMany('App\Models\Foods\Entry');
    }

    /**
     *
     * @return mixed
     */
    public function foodUnits()
    {
        return $this->hasMany('App\Models\Units\Unit')->where('for', 'food');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipes()
    {
        return $this->hasMany('App\Models\Foods\Recipe');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function weights()
    {
        return $this->hasMany('App\Models\Weights\Weight');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function journal()
    {
        return $this->hasMany('App\Models\Journal\Journal');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sleep()
    {
        return $this->hasMany('App\Models\Sleep\Sleep');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany('App\Models\Timers\Activity');
    }

    /**
     * Return the gravatar URL for the user
     * This method needs to be called getFieldAttribute
     * @return string
     */
    public function getGravatarAttribute()
    {
        $email = md5($this->email);

        return "https://secure.gravatar.com/avatar/{$email}?s=37&r=g&default=mm";
    }

}
