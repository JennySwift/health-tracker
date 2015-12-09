<?php

namespace App\Models\Timers;

use App\Traits\Models\Relationships\OwnedByUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Activity
 * @package App\Models\Timers
 */
class Activity extends Model
{
    use OwnedByUser;
    /**
     * @var string
     */
    protected $table = 'activities';

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timers()
    {
        return $this->hasMany('App\Models\Timers\Timer');
    }

    /**
     * Calculate how many minutes have been spent on the activity
     * for the day
     */
    public function calculateMinutesForDay($startOfDay, $endOfDay)
    {
        $minutes = 0;
        $timers = $this->timers()
            ->where(function($q) use ($startOfDay, $endOfDay)
            {
                $q->whereBetween('start', [$startOfDay, $endOfDay])
                    ->orWhereBetween('finish', [$startOfDay, $endOfDay]);
            })
            ->get();

        foreach ($timers as $timer) {
            //Todo: shouldn't have to do this. For some reason $this->timers is bringing up timers for the other user.
            if ($timer->user_id === Auth::id()) {
//                var_dump($timer->user_id);
                $minutes+= $timer->getTotalMinutesForDay(Carbon::createFromFormat('Y-m-d H:i:s', $startOfDay)->format('Y-m-d'));
            }

        }

        return $minutes;
    }
}
