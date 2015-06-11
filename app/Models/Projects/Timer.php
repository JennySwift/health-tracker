<?php namespace App\Models\Projects;

use Carbon\Carbon;
use DateInterval;
use Illuminate\Database\Eloquent\Model;

class Timer extends Model {

    protected $fillable = ['project_id', 'start'];

//    protected $with = ['project'];

    protected $appends = ['path', 'time', 'formatted_hours', 'formatted_minutes', 'formatted_seconds', 'price', 'formatted_price'];

	public $timestamps = false;

	/**
	 * Define relationships
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


    public function getTimeAttribute()
    {
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $this->start);
        $finish = Carbon::createFromFormat('Y-m-d H:i:s', $this->finish);
        $time = $finish->diff($start);
        $this->time = $time;

        return $time;
    }

    public function getFormattedHoursAttribute()
    {
        return sprintf("%02d", $this->time->h);
    }

    public function getFormattedMinutesAttribute()
    {
        return sprintf("%02d", $this->time->i);
    }

    public function getFormattedSecondsAttribute()
    {
        return sprintf("%02d", $this->time->s);
    }

    /**
     * Return the price for the project
     * Much the same as the equivalent method on the project model?
     * @return string
     */
    public function getPriceAttribute()
    {
        /**
         * The following code calculates the correct price.
         * I have commented it out because of the error it was giving me
         * (see comment below).
         */

        $price = 0;
//        $rate = $this->project->rate_per_hour;
//        var_dump($this->project->rate_per_hour);
//        dd('something');
        $time = $this->time;

        //For testing help:
        //$time = new DateInterval('PT1H15M30S');
        //$do_not_round_to_nearest_minute = true;

        //TODO Add user preference for rounding to the nearest minute?
//        if (isset($do_not_round_to_nearest_minute)) {
//            //User prefers not to round to the nearest minute.
//        }
//        else {
//            //Round to the nearest minute
//            if ($time->s > 30) {
//                $time->i = $time->i + 1;
//            }
//        }

//        $price+= $rate * $time->h;
//        $price+= $rate / 60 * $time->i;
//
//        if (isset($do_not_round_to_nearest_minute)) {
//            //User prefers not to round to the nearest minute.
//            //We need to therefore use seconds to calculate the price.
//            $price+= $rate / 3600 * $time->s;
//        }

        /**
         * @VP:
         * So if I dd $price, it is correct, and I get no error.
         * But when remove my dd and I try to return $price:
         * FatalErrorException in Grammar.php line 146:
         * Maximum function nesting level of '250' reached, aborting!
         *
         * Var_dumping $price for some reason shows price many times,
         * even when there are only 3 timers in the table.
         * I guess that explains the error, but why is it not just returning
         * one $price per timer?
         */

//        var_dump($price);

        return $price;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price);
    }
}
