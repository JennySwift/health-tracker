<?php namespace App\Http\Controllers\Units;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use JavaScript;

use App\Models\Units\Unit;

class UnitsController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Exercise units
	 */

	/**
	 * select
	 */
	
	public function getExerciseUnits()
	{
		return Unit::getExerciseUnits();
	}

	public function getFoodUnits()
	{
		return Unit::getFoodUnits();
	}

	/**
	 * insert
	 */
	
	public function insertfoodUnit(Request $request)
	{
		$name = $request->get('name');
		
		Unit::insert([
			'name' => $name,
			'for' => 'food',
			'user_id' => Auth::user()->id
		]);

		return Unit::getFoodUnits();
	}
	
	public function insertExerciseUnit(Request $request)
	{
		$name = $request->get('name');
		
		Unit::insert([
			'name' => $name,
			'for' => 'exercise',
			'user_id' => Auth::user()->id
		]);

		return Unit::getExerciseUnits();
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */

	public function deleteExerciseUnit(Request $request)
	{
		$id = $request->get('id');

		Unit::where('id', $id)->delete();
		return Unit::getExerciseUnits();
	}

	/**
	 * Food units
	 */
	
	/**
	 * delete
	 */
	
	public function deleteFoodUnit(Request $request)
	{
		$id = $request->get('id');
		Unit::where('id', $id)->delete();
		return Unit::getFoodUnits();
	}
}
