<?php namespace App\Models\Projects;

use App\Repositories\Projects\ProjectsRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;

/**
 * Class Project
 * @package App\Models\Projects
 */
class Project extends Model {

    /**
     * @var array
     */
    protected $fillable = ['description', 'rate_per_hour'];

    /**
     * @var array
     */
    protected $appends = ['price', 'formatted_price', 'total_time', 'total_time_formatted', 'path'];

    /**
     * @var array
     */
    protected $with = ['payer', 'payee'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payer()
	{
	    return $this->belongsTo('App\User');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payee()
	{
	    return $this->belongsTo('App\User');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timers()
	{
	    return $this->hasMany('App\Models\Projects\Timer');
	}

    /**
     * Return the URL of the project
     * it needs to be called getFieldAttribute
     * @return string
     */
    public function getPathAttribute()
    {
        return route('projects.show', $this->id);
    }

    /**
     * Return the price for the project
     * This method needs to be called getFieldAttribute
     * @return string
     */
    public function getPriceAttribute()
    {
        $rate = $this->rate_per_hour;
        $time = $this->total_time;
        $price = 0;

        $price+= $rate * $time['hours'];
        $price+= $rate / 60 * $time['minutes'];
        $price+= $rate / 3600 * $time['seconds'];

        return $price;
    }

    /**
     * Format price to two decimal places for displaying to the user
     * @return float|int|mixed
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
    }

    /**
     * Return the total time for the project
     * This method needs to be called getFieldAttribute
     * @return string
     */
    public function getTotalTimeAttribute()
    {
        $hours = 0;
        $minutes = 0;
        $seconds = 0;

        //I am using ->timers()->get() instead of ->timers to prevent
        //all the timers from being attached to the project (unnecessary data)
        foreach ($this->timers()->get() as $timer) {
            //Calculate hours, minutes and seconds
            $hours+= $timer->time->h;
            $minutes+= $timer->time->i;
            $seconds+= $timer->time->s;
        }

        //Stop total minutes from going above 59
        if ($minutes > 59) {
            $hours+= floor($minutes / 60);
            $minutes = $minutes % 60;
        }

        $time = [
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds
        ];

        return $time;
    }

    /**
     *
     * @return array
     */
    public function getTotalTimeFormattedAttribute()
    {
        $formatted = [
            'hours' => sprintf("%02d", $this->total_time['hours']),
            'minutes' => sprintf("%02d", $this->total_time['minutes']),
            'seconds' => sprintf("%02d", $this->total_time['seconds'])
        ];

        return $formatted;
    }

}
