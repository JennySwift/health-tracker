<?php namespace App\Models\Weights;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Weight extends Model {

	protected $table = 'weight';

	/**
	 * Define relationships
	 */

	public function user () {
		return $this->belongsTo('App\User');
	}

	/**
	 * select
	 */
	
	public static function getWeight($date) {
		$weight = static
			::where('date', $date)
			->where('user_id', Auth::user()->id)
			->pluck('weight');

		if (!$weight) {
			$weight = 0;
		}
		return $weight;
	}
	
	/**
	 * insert
	 */
	
	public static function insertWeight ($date, $weight) {
		static
			::insert([
				'date' => $date,
				'weight' => $weight,
				'user_id' => Auth::user()->id
			]);
	}

	/**
	 * update
	 */
	
	public static function updateWeight ($date, $weight) {
		static
			::where('date', $date)
			->where('user_id', Auth::user()->id)
			->update([
				'weight' => $weight
			]);
	}

	/**
	 * delete
	 */

}
