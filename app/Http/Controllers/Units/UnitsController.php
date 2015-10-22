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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $unit = new Unit([
            'name' => $request->get('name'),
            'for' => $request->get('for')
        ]);

        $unit->user()->associate(Auth::user());
        $unit->save();

        return $this->responseCreated($unit);
    }

    /**
     *
     * @param Unit $unit
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return $this->responseNoContent();
    }
}