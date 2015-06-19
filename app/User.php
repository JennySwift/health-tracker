<?php namespace App;

use App\Models\Projects\Payee;
use App\Models\Projects\Payer;
use App\Models\Projects\Project;
use App\Models\Projects\Timer;
use App\Repositories\Projects\ProjectsRepository;
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
    protected $appends = ['gravatar', 'owed_to_user', 'owed_by_user'];

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
     * Return the gravatar URL for the user
     * This method needs to be called getFieldAttribute
     * @return string
     */
    public function getGravatarAttribute()
    {
        $email = md5($this->email);

        return "https://secure.gravatar.com/avatar/{$email}?s=37&r=g&default=mm";
    }

    /**
     * Get the total amount the user owes the current user
     * @return mixed
     */
    public function getOwedToUserAttribute()
    {
        $payee = Payee::find(Auth::user()->id);

        //Find the projects belonging to the current user and $this user
        $projects_with_payer = Project::where('payee_id', $payee->id)
            ->where('payer_id', $this->id)
            ->lists('id');

        //Find the timers belonging to those projects,
        //but only those that have not been paid for
        $timers_with_payer = Timer::whereIn('project_id', $projects_with_payer)
            ->where('paid', 0)
            ->lists('id');

        //Find the amount owed
        $owed = Timer::whereIn('id', $timers_with_payer)
        ->sum('price');

        return $owed;
    }

    /**
     * Get the total amount the current user owes the user
     * @return mixed
     */
    public function getOwedByUserAttribute()
    {
        $payer = Payer::find(Auth::user()->id);

        //Find the projects belonging to the current user and $this user
        $projects_with_payee = Project::where('payer_id', $payer->id)
            ->where('payee_id', $this->id)
            ->lists('id');

        //Find the timers belonging to those projects,
        //but only those that have not been paid for
        $timers_with_payee = Timer::whereIn('project_id', $projects_with_payee)
            ->where('paid', 0)
            ->lists('id');

        //Find the amount owed
        $owed = Timer::whereIn('id', $timers_with_payee)
            ->sum('price');

        return $owed;
    }
}
