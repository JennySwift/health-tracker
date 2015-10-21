<?php namespace App\Http\Controllers\Foods;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Foods\Food;
use App\Models\Units\Unit;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;
use JavaScript;

/**
 * Class FoodsController
 * @package App\Http\Controllers\Foods
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
     *
     * @param Request $request
     * @return mixed
     */
    public function getAllFoodsWithUnits(Request $request)
    {
        return Food::getAllFoodsWithUnits();
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function insertFood(Request $request)
    {
        $name = $request->get('name');
        Food::insertFood($name);

        return Food::getAllFoodsWithUnits();
    }

    /**
     *
     * @param Request $request
     * @return array
     */
    public function insertUnitInCalories(Request $request)
    {
        $food = Food::find($request->get('food_id'));
        $unit_id = $request->get('unit_id');

        return Food::insertUnitInCalories($food, $unit_id);
    }

    /**
     *
     * @param Request $request
     * @return array
     */
    public function updateCalories(Request $request)
    {
        $food = Food::find($request->get('food_id'));
        $unit_id = $request->get('unit_id');
        $calories = $request->get('calories');

        DB::table('food_unit')
            ->where('food_id', $food->id)
            ->where('unit_id', $unit_id)
            ->update(['calories' => $calories]);

        return Food::getFoodInfo($food);
    }

    /**
     *
     * @param Request $request
     * @return array
     */
    public function updateDefaultUnit(Request $request)
    {
        $food = Food::find($request->get('food_id'));
        $unit_id = $request->get('unit_id');

        $food->update([
            'default_unit_id' => $unit_id
        ]);

        return Food::getFoodInfo($food);
    }

    /**
     *
     * @param Food $food
     * @return mixed
     * @throws \Exception
     */
    public function destroy(Food $food)
    {
        $food->delete();

        return Food::getAllFoodsWithUnits();
    }

    /**
     *
     * @param Request $request
     * @return array
     */
    public function deleteUnitFromCalories(Request $request)
    {
        $food = Food::forCurrentUser()->findOrFail($request->get('food_id'));
        $unit = Unit::forCurrentUser()->findOrFail($request->get('unit_id'));
        $food->units()->detach($unit->id);

        return Food::getFoodInfo($food);
	}
}