<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ExerciseTags extends Model {

	protected $table = 'exercise_tags';

	public static function getExerciseTags () {
		//gets all exercise tags
		$tags = static
			::where('user_id', Auth::user()->id)
			->select('id', 'name')
			->get();

		return $tags;
	}

}
