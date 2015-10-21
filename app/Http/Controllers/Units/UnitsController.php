<?php namespace App\Http\Controllers\Units;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Units\Unit;
use Auth;
use Illuminate\Http\Request;
use JavaScript;

/**
 * Class UnitsController
 * @package App\Http\Controllers\Units
 */
class UnitsController extends Controller
{

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
    public function insertFoodUnit(Request $request)
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
     * Todo: make sure it belongs to user
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
     * Todo: make sure it belongs to user
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