<?php namespace App\Models\Projects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Timer extends Model {

    protected $fillable = ['project_id', 'start'];

    protected $appends = ['path', 'time', 'formatted_hours', 'formatted_minutes', 'formatted_seconds', 'price'];

	public $timestamps = false;

	/**
	 * Define relationships
	 */
	
	public function project()
	{
	    return $this->belongsTo('App\Models\Projects\Project');
	}

    public function ratePerHour()
    {
        return $this->project->rate_per_hour;
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
//        dd($this->ratePerHour());
//        $rate = $this->ratePerHour();
//        var_dump($this->project);
//        return $rate;
        $time = $this->time;
        $price = 0;

//        $price+= $rate * $time->h;
//        $price+= $rate / 60 * $time->i;
//        $price+= $rate / 3600 * $time->s;
//        var_dump($price);
        return $price;
    }
}
