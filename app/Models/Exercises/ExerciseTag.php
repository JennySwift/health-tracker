<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use Auth;

/**
 * pivot table model
 */

class ExerciseTag extends Model {

	protected $table = 'exercise_tag';

	public static function getTagsForExercise ($exercise_id) {
		//gets tags associated with each exercise
		$tags = static
			::where('exercise_id', $exercise_id)
			->join('exercise_tags', 'exercise_tag.tag_id', '=', 'exercise_tags.id')
			->select('exercise_tags.id', 'name')
			->get();

		return $tags;
	}

	public static function insertExerciseTag ($exercise_id, $tag_id) {
		//inserts a tag into an exercise
		static::insert([
			'exercise_id' => $exercise_id,
			'tag_id' => $tag_id,
			'user_id' => Auth::user()->id
		]);
	}

}
