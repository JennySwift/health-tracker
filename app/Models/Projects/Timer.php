<?php namespace App\Models\Projects;

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
     */
    protected $fillable = ['project_id', 'start'];

    /**
     * @var array
     */
    protected $appends = ['path', 'time', 'formatted_hours', 'formatted_minutes', 'formatted_seconds', 'formatted_paid_at', 'formatted_start', 'formatted_finish'];

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
     *
     * @return bool|DateInterval
     */
    public function getTimeAttribute()
    {
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $this->start);
        $finish = Carbon::createFromFormat('Y-m-d H:i:s', $this->finish);
        $time = $finish->diff($start);
        $this->time = $time;

        return $time;
    }

    /**
     *
     * @return string
     */
    public function getFormattedHoursAttribute()
    {
        return sprintf("%02d", $this->time->h);
    }

    /**
     *
     * @return string
     */
    public function getFormattedMinutesAttribute()
    {
        return sprintf("%02d", $this->time->i);
    }

    /**
     *
     * @return string
     */
    public function getFormattedSecondsAttribute()
    {
        return sprintf("%02d", $this->time->s);
    }
}
