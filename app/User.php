<?php namespace App;

use App\Models\Projects\Project;
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

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
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
     * Define relationships
     */

    //projects

    public function payers()
    {
        return $this->belongsToMany('App\User', 'payee_payer', 'payee_id', 'payer_id');
    }

    public function getPayersForCurrentUser()
    {
        $payers = $this->payers;

//        Figure out how much the payer owes the payee
//        Add this owed value to the $payer
//        $timer->price has not been coded yet so this won't yet work
//        foreach ($payers as $payer) {
//            $payer->projectsAsPayer = $payer->projectsAsPayer;
////            dd($payer->projectsAsPayer);
//
//            $owed = 0;
//            foreach($payer->projectsAsPayer as $project) {
//
//                foreach($project->timers as $timer) {
//                    if (!$timer->paid) {
//                        $owed+= $timer->price;
//                    }
//                }
//
//            }
//            $payer->owed = $owed;
//        }
//        dd($payers);
        return $payers;
    }

    public function payees()
    {
        return $this->belongsToMany('App\User', 'payee_payer', 'payer_id', 'payee_id');
    }

    /**
     * Get all the projects where the user is the payee
     */
    public function projectsAsPayee()
    {
        return $this->hasMany('App\Models\Projects\Project', 'payee_id');
    }

    /**
     * Get all the projects where the user is the payer
     * and the logged in user is payee
     */
    public function projectsAsPayer()
    {
        return $this->hasMany('App\Models\Projects\Project', 'payer_id')
            ->with('payee')
            ->with('payer');
    }

    /**
     * End projects
     */

    //tags
    public function recipeTags()
    {
        return $this->hasMany('App\Models\Tags\Tag')->where('for', 'recipe');
    }

    public function exerciseTags()
    {
        return $this->hasMany('App\Models\Tags\Tag')->where('for', 'exercise');
    }

    //exercises
    public function exercises()
    {
        return $this->hasMany('App\Models\Exercises\Exercise');
    }

    public function exerciseEntries()
    {
        return $this->hasMany('App\Models\Exercises\Entry');
    }

    public function exerciseSeries()
    {
        return $this->hasMany('App\Models\Exercises\Series');
    }

    public function exerciseUnits()
    {
        return $this->hasMany('App\Models\Units\Unit')->where('for', 'exercise');
    }

    public function workouts()
    {
        return $this->hasMany('App\Models\Exercises\Workout');
    }

    //foods
    public function foods()
    {
        return $this->hasMany('App\Models\Foods\Food');
    }

    public function foodEntries()
    {
        return $this->hasMany('App\Models\Foods\Entry');
    }

    public function foodUnits()
    {
        return $this->hasMany('App\Models\Units\Unit')->where('for', 'food');
    }

    public function recipes()
    {
        return $this->hasMany('App\Models\Foods\Recipe');
    }

    //weight
    public function weights()
    {
        return $this->hasMany('App\Models\Weights\Weight');
    }

    //journal
    public function journal()
    {
        return $this->hasMany('App\Models\Journal\Journal');
    }

    /**
     * End of defining relationships
     */

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
     * duplicate from projects repository
     * @param $time
     * @return array
     */
//    public function formatTimeForUser($time)
//    {
//        $formatted = [
//            'hours' => sprintf("%02d", $time['hours']),
//            'minutes' => sprintf("%02d", $time['minutes']),
//            'seconds' => sprintf("%02d", $time['seconds'])
//        ];
//
//        return $formatted;
//    }
}
