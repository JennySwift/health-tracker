<?php namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

	protected $fillable = ['description', 'rate_per_hour'];

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

}
