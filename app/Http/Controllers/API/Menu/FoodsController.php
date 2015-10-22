<?php namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Menu\Food;
use App\Models\Units\Unit;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;
use JavaScript;

/**
 * Class FoodsController
 * @package App\Http\Controllers\Menu
 */
class FoodsController extends Controller
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
     * @param Request $request
     * @return mixed
     */
    public function index()
    {
        return Food::getAllFoodsWithUnits();
    }

    /**
     *
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
     *
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
     *
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