<?php namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Model;

class Timer extends Model {

    protected $fillable = ['project_id', 'start'];

    protected $appends = ['path'];

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

}
