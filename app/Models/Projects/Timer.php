<?php namespace App\Models\Projects;

use App\Exceptions\UncallableMethod;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Timer
 * @package App\Models\Projects
 */
class Timer extends Model {

    /**
     * @var array
     * @TODO project_id shouldn't be fillable
     */
    protected $fillable = ['project_id', 'start', 'finish', 'paid', 'time_of_payment'];

    /**
     * @var array
     * @TODO The front end should take care of formatting anything
     */
    protected $appends = ['path', 'time', 'totalTime', 'formatted_hours', 'formatted_minutes', 'formatted_seconds', 'formatted_paid_at', 'formatted_start', 'formatted_finish'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
	{
	    return $this->belongsTo('App\Models\Projects\Project');
	}

    /**
     * Return the URL of the project
     * it needs to be called getFieldAttribute
     * @return string
     */
    public function getPathAttribute()
    {
        return route('timers.destroy', $this->id);
    }

    /**
     *
     * @return string
     */
    public function getFormattedPaidAtAttribute()
    {
        /**
         * @VP:
         * How would I name this attribute the same as the database column?
         * I tried it but it errored unless I gave it a different attribute name.
         *
         * You would have to call the method getTimeOfPayementAttribute() and use $this->attributes['time_of_payment']
         */

        if (!$this->time_of_payment) {
            return '';
        }

        return Carbon::createFromFormat('Y-m-d H:i:s', $this->time_of_payment)->format('d/m/y');
    }

    /**
     *
     * @return string
     */
    public function getFormattedStartAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->start)->format('d/m/y H:i:s');
    }

    /**
     * Get start attribute
     * @param $value
     * @return static
     */
    public function getStartAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value);
    }

    /**
     *
     * @return string
     */
    public function getFormattedFinishAttribute()
    {
        if (!$this->finish) {
            return '';
        }

        return Carbon::createFromFormat('Y-m-d H:i:s', $this->finish)->format('d/m/y H:i:s');
    }

    /**
     * Get finish attribute
     * @param $value
     * @return static
     */
    public function getFinishAttribute($value)
    {
        if(!is_null($value))
        {
            return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['finish']);
        }

        return $value;
    }


    /**
     *
     * @return DateInterval|null
     */
    public function getTimeAttribute()
    {
        if(! $this->hasFinishTime()) {
            return null;
        }

        //$start = Carbon::createFromFormat('Y-m-d H:i:s', $this->start);
//        dd($this->finish);

        /**
         * @VP:
         * I was getting an error when I started a new timer, because the finish time
         * was null. How would I return $time here with h, i, and s equal to 0?
         * My attempts here didn't work.
         *
         * See condition at the beginning of the method
         */


//        if (!$this->finish) {
//            return [
//                'h' => 0,
//                'i' => 0,
//                's' => 0
//            ];
//        }

//        $this->time->h = 0;
//        $this->time->i = 0;
//        $this->time->s = 0;
//
//        if (!$this->finish) {
//            return false;
//        }

//        dd($this->time);

        //$finish = Carbon::createFromFormat('Y-m-d H:i:s', $this->finish);
        //$time = $finish->diff($start);
        // $this->time = $time; a get method shouldn't modify the model in any way

        //return $time;
        return $this->finish->diff($this->start);
    }

    /**
     *
     * @return string
     */
    public function getFormattedHoursAttribute()
    {
        if (!$this->time) {
            return '';
        }

        return sprintf("%02d", $this->time->h);
    }

    /**
     *
     * @return string
     */
    public function getFormattedMinutesAttribute()
    {
        if (!$this->time) {
            return '';
        }
        return sprintf("%02d", $this->time->i);
    }

    /**
     *
     * @return string
     */
    public function getFormattedSecondsAttribute()
    {
        if (!$this->time) {
            return '';
        }
        return sprintf("%02d", $this->time->s);
    }

    /**
     * Calculate the total time span
     * @return bool|DateInterval|null
     */
    public function getTotalTimeAttribute()
    {
        if( ! $this->hasFinishTime() ) {
            return null;
        }

        $carbon_start = Carbon::createFromFormat('Y-m-d H:i:s', $this->start);
        $carbon_finish = Carbon::createFromFormat('Y-m-d H:i:s', $this->finish);

        return $carbon_finish->diff($carbon_start);
    }

    /**
     * Fetch the rate attribute on project and store it on the Timer
     * @return mixed
     */
    public function getRateAttribute()
    {
        return $this->project->rate_per_hour;
    }

    /**
     * Is the timer finished?
     * @return bool
     */
    public function hasFinishTime()
    {
        return ! is_null($this->finish);
    }

    /**
     * Does the timer has a time property (=> does the calculateTotalTime method has been called?)
     * @return bool
     */
    public function hasTotalTime()
    {
        return (boolean) $this->totalTime;
    }

    /**
     * Does the timer project has a rate?
     * @return bool
     */
    public function hasRate()
    {
        return (boolean) $this->rate;
    }

    /**
     * Calculate the price
     * @return float
     */
    public function calculatePrice()
    {
        $price = 0;

        if(!$this->hasTotalTime()) {
            throw new UncallableMethod;
        }

//        if ($this->totalTime->s > 30) {
//            $this->totalTime->i = $this->totalTime->i + 1;
//        }

        $price += $this->rate * $this->totalTime->h;
        $price += $this->rate / 60 * $this->totalTime->i;
        //$price += $this->rate / 3600 * $this->totalTime->s;

        $this->attributes['price'] = $price;
    }

}
