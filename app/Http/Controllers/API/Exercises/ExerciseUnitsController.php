<?php namespace App\Http\Controllers\API\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\UnitTransformer;
use App\Models\Units\Unit;
use App\Repositories\UnitsRepository;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Http\Response;

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
     * GET /api/units
     * @return Response
     */
    public function index()
    {
        $units = $this->unitsRepository->getExerciseUnits();
        $units = $this->transform($this->createCollection($units, new UnitTransformer))['data'];
        return response($units, Response::HTTP_OK);
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
        try {
            $unit->delete();
            return $this->responseNoContent();
        }
        catch (\Exception $e) {
            //Integrity constraint violation
            if ($e->getCode() === '23000') {
                $message = 'Unit could not be deleted. It is in use.';
            }
            else {
                $message = 'There was an error';
            }
            return response([
                'error' => $message,
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }

}