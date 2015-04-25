<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Exercise_tag;

class Exercise extends Model {

	public static function getExercises () {
	    $exercises = Exercise
	    	::where('exercises.user_id', Auth::user()->id)
	    	->leftJoin('exercise_units', 'default_exercise_unit_id', '=', 'exercise_units.id')
	    	->leftJoin('exercise_series', 'exercises.series_id', '=', 'exercise_series.id')
	    	->select('exercises.id', 'exercises.name', 'exercises.description', 'exercises.step_number', 'exercise_series.name as series_name', 'default_exercise_unit_id', 'default_quantity', 'exercise_units.name AS default_exercise_unit_name')
	    	->orderBy('series_name', 'asc')
	    	->orderBy('step_number', 'asc')
	    	->get();

	    foreach ($exercises as $exercise) {
	    	$id = $exercise->id;
	    	$tags = Exercise_tag::getTagsForExercise($id);
	    	$exercise->tags = $tags;
	    }

	    return $exercises;
	}

	public static function getDefaultExerciseQuantity ($exercise_id) {
		$quantity = Exercise
			::where('id', $exercise_id)
			->pluck('default_quantity');

		return $quantity;
	}

	public static function getDefaultExerciseUnitId ($exercise_id) {
		$default = Exercise
			::where('id', $exercise_id)
			->pluck('default_exercise_unit_id');

		return $default;
	}
	

}
