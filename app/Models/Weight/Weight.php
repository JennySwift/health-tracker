<?php namespace App\Models\Weight;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Weight extends Model {

	protected $table = 'weight';

	public static function getWeight($date) {
		$weight = Weight
			::where('date', $date)
			->where('user_id', Auth::user()->id)
			->pluck('weight');

		if (!$weight) {
			$weight = 0;
		}
		return $weight;
	}

	public static function updateWeight ($date, $weight) {
		Weight
			::where('date', $date)
			->where('user_id', Auth::user()->id)
			->update([
				'weight' => $weight
			]);
	}

	public static function insertWeight ($date, $weight) {
		Weight
			::insert([
				'date' => $date,
				'weight' => $weight,
				'user_id' => Auth::user()->id
			]);
	}

}
