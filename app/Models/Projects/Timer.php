<?php namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Model;

class Timer extends Model {

    protected $fillable = ['project_id', 'start'];

	public $timestamps = false;

	/**
	 * Define relationships
	 */
	
	public function project()
	{
	    return $this->belongsTo('App\Models\Projects\Project');
	}

}
