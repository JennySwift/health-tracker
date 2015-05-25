<?php namespace App\Models\Weights;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Weight extends Model {

	/**
	 * Define relationships
	 */
	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
