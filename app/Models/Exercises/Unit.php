<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Unit extends Model {

	protected $table = 'exercise_units';

	public static function getExerciseUnits () {
	    $result = static
	    	::where('user_id', Auth::user()->id)
	    	->select('id', 'name')
	    	->orderBy('name', 'asc')
	    	->get();

	    //so that it is an array, not an object
	    $exercise_units = array();
	    foreach ($result as $unit) {
	    	$exercise_units[] = $unit;
	    }

	    return $exercise_units;
	}

}
