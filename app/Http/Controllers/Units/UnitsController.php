<?php namespace App\Http\Controllers\Units;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use JavaScript;

use App\Models\Units\Unit;

/**
 * Class UnitsController
 * @package App\Http\Controllers\Units
 */
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
     *
     * @return mixed
     */
    public function getExerciseUnits()
	{
		return Unit::getExerciseUnits();
	}

    /**
     *
     * @return mixed
     */
    public function getFoodUnits()
	{
		return Unit::getFoodUnits();
	}

    /**
     *
     * @param Request $request
     * @return mixed
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

    /**
     *
     * @param Request $request
     * @return mixed
     */
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
     *
     * @param Request $request
     * @return mixed
     */
    public function deleteExerciseUnit(Request $request)
	{
		$id = $request->get('id');

		Unit::where('id', $id)->delete();
		return Unit::getExerciseUnits();
	}

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function deleteFoodUnit(Request $request)
	{
		$id = $request->get('id');
		Unit::where('id', $id)->delete();
		return Unit::getFoodUnits();
	}
}