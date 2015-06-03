<?php namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

	protected $fillable = ['description', 'rate_per_hour'];

    protected $appends = ['path'];

	/**
	 * Define relationships
	 */
	
	public function payer()
	{
	    return $this->belongsTo('App\User');
	}

	public function payee()
	{
	    return $this->belongsTo('App\User');
	}

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
        return route('projects.destroy', $this->id); // http://tracker.dev/projects/$id
    }
}
