<?php namespace App\Models\Projects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Timer extends Model {

    protected $fillable = ['project_id', 'start'];

    protected $appends = ['path', 'time', 'formatted_hours', 'formatted_minutes', 'formatted_seconds'];

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
}
