<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Exercises\ExerciseTag;

class Exercise extends Model {

	protected $fillable = ['name', 'default_exercise_unit_id', 'description', 'default_quantity', 'step_number', 'series_id'];

	/**
	 * Define relationships
	 */

	public function user () {
	    return $this->belongsTo('App\User');
	}

	public function unit () {
		//the second argument is the name of the field, because if I don't specify it, it will look for unit_id.
	    return $this->belongsTo('App\Models\Units\Unit', 'default_exercise_unit_id');
	}

	public function series () {
	    return $this->belongsTo('App\Models\Exercises\Series');
	}

	public function entries () {
	    return $this->hasMany('App\Models\Exercises\Entry');
	}

	/**
	 * select
	 */
	
	public static function getExercises () {
	    $exercises = static
	    	::where('exercises.user_id', Auth::user()->id)
	    	->leftJoin('units', 'default_exercise_unit_id', '=', 'units.id')
	    	->leftJoin('exercise_series', 'exercises.series_id', '=', 'exercise_series.id')
	    	->select('exercises.id', 'exercises.name', 'exercises.description', 'exercises.step_number', 'exercise_series.name as series_name', 'default_exercise_unit_id', 'default_quantity', 'units.name AS default_exercise_unit_name')
	    	->orderBy('series_name', 'asc')
	    	->orderBy('step_number', 'asc')
	    	->get();

	    foreach ($exercises as $exercise) {
	    	$id = $exercise->id;
	    	$tags = ExerciseTag::getTagsForExercise($id);
	    	$exercise->tags = $tags;
	    }

	    return $exercises;
	}

	public static function getDefaultExerciseQuantity ($exercise_id) {
		$quantity = static
			::where('id', $exercise_id)
			->pluck('default_quantity');

		return $quantity;
	}

	public static function getDefaultExerciseUnitId ($exercise_id) {
		$default = static
			::where('id', $exercise_id)
			->pluck('default_exercise_unit_id');

		return $default;
	}
	
	/**
	 * insert
	 */
	
	/**
	 * update
	 */
	
	/**
	 * delete
	 */

}
