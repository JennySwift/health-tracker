<?php namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\UnitTransformer;
use App\Models\Units\Unit;
use App\Repositories\UnitsRepository;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class FoodUnitsController
 * @package App\Http\Controllers\Units
 */
class FoodUnitsController extends Controller
{
    /**
     * @var UnitsRepository
     */
    private $unitsRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UnitsRepository $unitsRepository)
    {
        $this->middleware('auth');
        $this->unitsRepository = $unitsRepository;
    }

    /**
     * GET /api/units
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $units = Unit::forCurrentUser()
            ->where('for', 'food')
            ->orderBy('name', 'asc')
            ->get();

        $units = $this->transform($this->createCollection($units, new UnitTransformer))['data'];

        if ($request->get('includeCaloriesForSpecificFood')) {
            //Add the calories for a specific food to each unit, if the calories exist
            foreach ($units as $index => $unit) {
                $calories = DB::table('food_unit')
                    ->where('food_id', $request->get('food_id'))
                    ->where('unit_id', $unit['id'])
                    ->pluck('calories');

                $units[$index]['calories'] = $calories;
            }
        }

        return response($units, Response::HTTP_OK);
    }

    /**
     * POST /api/units
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $unit = new Unit($request->only([
            'name'
        ]));
        $unit->for = 'food';

        $unit->user()->associate(Auth::user());
        $unit->save();

        $unit = $this->transform($this->createItem($unit, new UnitTransformer))['data'];
        return response($unit, Response::HTTP_CREATED);
    }

    /**
    * UPDATE /api/Units/{Units}
    * @param Request $request
    * @param Unit $unit
    * @return Response
    */
    public function update(Request $request, Unit $unit)
    {
        // Create an array with the new fields merged
        $data = array_compare($unit->toArray(), $request->only([
            'name'
        ]));

        $unit->update($data);

        $unit = $this->transform($this->createItem($unit, new UnitTransformer))['data'];
        return response($unit, Response::HTTP_OK);
    }

    /**
     * @VP:
     * Should the error handling go somewhere else?
     * I tried doing it in Handler.php but I wanted the model name (unit),
     * and there was no $e->getModel() method.
     * @param Unit $unit
     * @return Response
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