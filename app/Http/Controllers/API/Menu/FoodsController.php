<?php namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Menu\Food;
use App\Models\Units\Unit;
use App\Repositories\FoodsRepository;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;

/**
 * Class FoodsController
 * @package App\Http\Controllers\Menu
 */
class FoodsController extends Controller
{
    /**
     * @var FoodsRepository
     */
    private $foodsRepository;

    /**
     * Create a new controller instance.
     *
     * @param FoodsRepository $foodsRepository
     */
    public function __construct(FoodsRepository $foodsRepository)
    {
        $this->middleware('auth');
        $this->foodsRepository = $foodsRepository;
    }

    /**
     * GET api/foods/{foods}
     * @param $id
     * @return array
     */
    public function show($id)
    {
        $food = Food::find($id);

        return Food::getFoodInfo($food);
    }

    /**
     * Get all foods with units
     * GET api/foods
     * @param Request $request
     * @return mixed
     */
    public function index()
    {
        return $this->foodsRepository->getFoods();
    }

    /**
     * POST api/foods
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $food = new Food([
            'name' => $request->get('name')
        ]);

        $food->user()->associate(Auth::user());
        $food->save();

        return $this->responseCreated($food);
    }

    /**
     * PUT api/foods/{foods}
     * @param Food $food
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Food $food, Request $request)
    {
        $unit = Unit::find($request->get('default_unit_id'));
        $food->defaultUnit()->associate($unit);
        $food->save();

        return $this->responseOk($food);
    }

    /**
     * DELETE api/foods/{foods}
     * @param Food $food
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Food $food)
    {
        $food->delete();

        return $this->responseNoContent();
    }
}