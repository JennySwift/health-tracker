<?php namespace App\Models\Timers;

use Illuminate\Database\Eloquent\Model;

class Time extends Model {

	/**
	 * Define relationships
	 */
	
	public function timer()
	{
	    return $this->belongsTo('App\Models\Timers\Timer');
	}

}
