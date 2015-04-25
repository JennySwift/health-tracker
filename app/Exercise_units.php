<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Exercise_units extends Model {

	public static function getExerciseUnits () {
	    $result = Exercise_units
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
