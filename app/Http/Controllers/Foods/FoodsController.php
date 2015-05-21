<?php namespace App\Http\Controllers\Foods;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use Debugbar;
use JavaScript;

/**
 * Models
 */
use App\Models\Foods\Food;
use App\Models\Foods\Recipe;
use App\Models\Tags\Tag;
use App\User;

class FoodsController extends Controller {

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
	 * Index
	 */

	public function index()
	{
		JavaScript::put([
			'foods_with_units' => Food::getAllFoodsWithUnits(),
			'recipes' => Recipe::where('user_id', Auth::user()->id)->get(),
			'recipe_tags' => Tag::getRecipeTags()
		]);
		// return Food::getAllFoodsWithUnits();
		return view('foods');
	}

	/**
	 * select
	 */

	public function getFoodInfo(Request $request)
	{
		$food = Food::find($request->get('food_id'));
		return Food::getFoodInfo($food);
	}

	public function getAllFoodsWithUnits(Request $request)
	{
		return Food::getAllFoodsWithUnits();
	}

	/**
	 * insert
	 */

	public function insertFood(Request $request)
	{
		$name = $request->get('name');
		Food::insertFood($name);

		return Food::getAllFoodsWithUnits();
	}

	/**
	 * update
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
	 * delete
	 */

	public function deleteFood(Request $request)
	{
		$id = $request->get('id');
		Food::where('id', $id)->delete();
		return Food::getAllFoodsWithUnits();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	// public function index()
	// {
	// 	//
	// }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
