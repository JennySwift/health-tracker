<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Exercise extends Model {

	//the following is from session with Valentin, to show how I would be able to reuse my code without having everything in a functions.php file.

	// public static function autocomplete($exercise) {
	// 	return static
	// 		::where('name', 'LIKE', $exercise)
	// 		->where('user_id', Auth::user()->id)
	// 		->select('id', 'name', 'description', 'default_exercise_unit_id', 'default_quantity')
	// 		->get();
	// }
	

}
