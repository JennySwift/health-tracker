<?php namespace App\Http\Controllers\API\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\UnitTransformer;
use App\Models\Units\Unit;
use App\Repositories\UnitsRepository;
use Illuminate\Http\Request;
use Auth;

/**
 * @VP:
 * This is much the same as FoodUnitsController. I did it so I could have
 * an index method for both food and exercise units. Good idea or not?
 */


/**
 * Class ExerciseUnitsController
 * @package App\Http\Controllers\Exercises
 */
class ExerciseUnitsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @param UnitsRepository $unitsRepository
     */
    public function __construct(UnitsRepository $unitsRepository)
    {
        $this->middleware('auth');
        $this->unitsRepository = $unitsRepository;
    }

    /**
     *
     * @return mixed
     */
    public function index()
    {
        return $this->unitsRepository->getExerciseUnits();
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
            'for' => 'exercise'
        ]);

        $unit->user()->associate(Auth::user());
        $unit->save();

        return $this->responseCreatedWithTransformer($unit, new UnitTransformer);
    }

    /**
     *
     * @param Unit $unit
     * @param Request $request
     * @return mixed
     */
    public function update(Unit $unit, Request $request)
    {
        $unit->name = $request->get('name');
        $unit->save();

        return $this->responseOkWithTransformer($unit, new UnitTransformer);
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