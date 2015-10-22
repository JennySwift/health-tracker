<?php namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\UnitTransformer;
use App\Models\Units\Unit;
use App\Repositories\UnitsRepository;
use Auth;
use Illuminate\Http\Request;

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
     *
     * @return mixed
     */
    public function index()
    {
        return $this->unitsRepository->getFoodUnits();
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
            'for' => 'food'
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