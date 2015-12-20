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
    protected $fillable = ['name', 'color'];

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
     * Get the timer's for an activity whose start or finish
     * is on a particular date
     * @param $startOfDay
     * @param $endOfDay
     * @return mixed
     */
    public function getTimersForDay($startOfDay, $endOfDay)
    {
        return $this->timers()
            ->where(function($q) use ($startOfDay, $endOfDay)
            {
                $q->whereBetween('start', [$startOfDay, $endOfDay])
                    ->orWhereBetween('finish', [$startOfDay, $endOfDay]);
            })
            ->get();
    }

    /**
     *
     * @return int
     */
    public function totalMinutesForAllTime()
    {
        $total = 0;
        foreach ($this->timers as $timer) {
            if ($timer->finish) {
                $total+= $timer->totalMinutes;
            }
        }

        return $this->totalMinutesForAllTime = $total;
    }

    /**
     * Calculate how many minutes have been spent on the activity
     * for the day
     * @param Carbon $startOfDay
     * @param Carbon $endOfDay
     * @return int
     */
    public function totalMinutesForDay(Carbon $startOfDay, Carbon $endOfDay)
    {
        $total = 0;
        $timers = $this->getTimersForDay($startOfDay, $endOfDay);

        foreach ($timers as $timer) {
            $total+= $timer->getTotalMinutesForDay($startOfDay);
        }

        return $this->totalMinutesForDay = $total;
    }

    /**
     *
     * @return float
     */
    public function hoursForDay()
    {
        return $this->hoursForDay = floor($this->totalMinutesForDay / 60);
    }

    /**
     * Not the total minutes for the day
     * @return int
     */
    public function minutesForDay()
    {
        return $this->minutesForDay = $this->totalMinutesForDay % 60;
    }
}
