<?php namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\Menu\FoodTransformer;
use App\Models\Menu\Food;
use App\Models\Units\Unit;
use App\Repositories\FoodsRepository;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     * GET /api/foods/{foods}
     * @param Food $food
     * @return Response
     */
    public function show(Food $food)
    {
        $food = $this->transform($this->createItem($food, new FoodTransformer))['data'];
        return response($food, Response::HTTP_OK);
    }

    /**
     * GET api/foods
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($request->get('typing')) {
            $foods = Food::forCurrentUser()
                ->where('name', 'LIKE', '%' . $request->get('typing') . '%')
                ->with('units')
                ->get();
            return $this->transform($this->createCollection($foods, new FoodTransformer),['units']);
        }

        return $this->foodsRepository->getFoods();
    }

    /**
     * POST /api/foods
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $food = new Food($request->only([
            'name'
        ]));
        $food->user()->associate(Auth::user());
        $food->save();

        $food = $this->transform($this->createItem($food, new FoodTransformer))['data'];
        return response($food, Response::HTTP_CREATED);
    }

    /**
    * UPDATE /api/foods/{foods}
    * @param Request $request
    * @param Food $food
    * @return Response
    */
    public function update(Request $request, Food $food)
    {
        if ($request->get('updatingCalories')) {
            //We are updating the calories for one of the food's units
            $food->units()->updateExistingPivot($request->get('unit_id'), [
                'calories' => $request->get('calories')
            ]);
        }
        else {
            // Create an array with the new fields merged
            $data = array_compare($food->toArray(), $request->only([
                'name'
            ]));

            $food->update($data);

            if ($request->has('default_unit_id')) {
                $food->defaultUnit()->associate(Unit::findOrFail($request->get('default_unit_id')));
                $food->save();
            }

            if ($request->has('unit_ids')) {
                $food->units()->sync($request->get('unit_ids'));
            }

        }

        $food = $this->transform($this->createItem($food, new FoodTransformer))['data'];
        return response($food, Response::HTTP_OK);
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