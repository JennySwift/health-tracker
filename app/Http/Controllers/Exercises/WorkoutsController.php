<?php namespace App\Http\Controllers\Exercises;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

use App\Models\Exercises\Workout;

class WorkoutsController extends Controller {

	/**
	 * select
	 */
	
	/**
	 * insert
	 */
	
	public function store(Request $request)
	{
		$name = $request->get('name');

		Workout::insert([
			'name' => $name,
			'user_id' => Auth::user()->id
		]);

		return Workout::getWorkouts();
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */

}
