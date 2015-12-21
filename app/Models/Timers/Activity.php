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
     * is on a particular date or week
     * @param Carbon $date
     * @return mixed
     */
    public function getTimersForTimePeriod(Carbon $date)
    {
        $date = $date->format('Y-m-d') . '%';

        //Todo: this query is much the same as in timers repository for timers index method.
        //Create a query scope?
        return $this->timers()
            ->where(function ($q) use ($date) {
                $q->where('finish', 'LIKE', $date)
                    ->orWhere('start', 'LIKE', $date);
            })
            ->get();

        //This was problematic because it included timers that started at midnight
        //(on the start of the next day)

//        return $this->timers()
//            ->where(function($q) use ($start, $end)
//            {
//                $q->whereBetween('start', [$start, $end])
//                    ->orWhereBetween('finish', [$start, $end]);
//            })
//            ->get();
    }

    /**
     *
     * @return int
     */
    public function totalMinutesForAllTime()
    {
        $total = 0;
        foreach ($this->timers()->get() as $timer) {
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
    public function calculateTotalMinutesForDay(Carbon $startOfDay, Carbon $endOfDay)
    {
        $total = 0;
        $timers = $this->getTimersForTimePeriod($startOfDay);

        foreach ($timers as $timer) {
            $total+= $timer->getTotalMinutesForDay($startOfDay);
        }

        return $total;
    }

    /**
     * Calculate how many minutes have been spent on the activity
     * for the week
     * @param Carbon $startOfWeek
     * @param Carbon $endOfWeek
     * @return int
     */
    public function calculateTotalMinutesForWeek(Carbon $startOfWeek, Carbon $endOfWeek)
    {
        $total = 0;
        $day = $endOfWeek->copy();

        while ($day >= $startOfWeek) {
            $total+= $this->calculateTotalMinutesForDay($day->copy()->startOfDay(), $day->copy()->endOfDay());
            $day = $day->subDay();
        }

        return $total;
    }

    /**
     * If the week isn't over yet, instead of dividing by 7, do the
     * division according to how many days of the week have happened
     * So if today is Tuesday, divide by 3, for example.
     * @param $date
     * @return float
     */
    public function calculateAverageMinutesPerDayForWeek($date)
    {
        $date = Carbon::createFromFormat('Y-m-d', $date);
        if (Carbon::today() < $date->copy()->endOfWeek()) {
            //The week isn't over yet.
            return round($this->totalMinutesForWeek / (Carbon::today()->dayOfWeek + 1));

        }
        else {
            return round($this->totalMinutesForWeek / 7);
        }

    }


    /**
     *
     * @return float
     */
//    public function hoursForDay()
//    {
//        return $this->hoursForDay = floor($this->totalMinutesForDay / 60);
//    }

    /**
     * Not the total minutes for the day
     * @return int
     */
//    public function minutesForDay()
//    {
//        return $this->minutesForDay = $this->totalMinutesForDay % 60;
//    }
}
