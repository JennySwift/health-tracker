<?php namespace App\Models\Timers;

use Illuminate\Database\Eloquent\Model;

class Timer extends Model {

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

}
