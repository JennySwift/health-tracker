<?php namespace App\Models\Projects;

use Carbon\Carbon;
use DateInterval;
use Illuminate\Database\Eloquent\Model;

class Timer extends Model {

    protected $fillable = ['project_id', 'start'];

    protected $appends = ['path', 'time', 'formatted_hours', 'formatted_minutes', 'formatted_seconds', 'formatted_paid_at', 'formatted_start', 'formatted_finish'];

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

    public function getFormattedPaidAtAttribute()
    {
        /**
         * @VP:
         * How would I name this attribute the same as the database column?
         * I tried it but it errored unless I gave it a different attribute name.
         */

        if (!$this->time_of_payment) {
            return '';
        }

        return Carbon::createFromFormat('Y-m-d H:i:s', $this->time_of_payment)->format('d/m/y');
    }

    public function getFormattedStartAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->start)->format('d/m/y H:i:s');
    }

    public function getFormattedFinishAttribute()
    {
        if (!$this->finish) {
            return '';
        }

        return Carbon::createFromFormat('Y-m-d H:i:s', $this->finish)->format('d/m/y H:i:s');
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
}
