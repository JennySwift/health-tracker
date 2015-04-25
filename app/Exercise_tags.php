<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Exercise_tags extends Model {

	protected $table = 'exercise_tags';

	public static function getExerciseTags () {
		//gets all exercise tags
		$tags = Exercise_tags
			::where('user_id', Auth::user()->id)
			->select('id', 'name')
			->get();

		return $tags;
	}

}
