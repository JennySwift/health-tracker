<?php namespace App\Http\Controllers\Recipes;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use DB;
use Debugbar;

/**
 * Models
 */
use App\Models\Foods\Recipe;
use App\Models\Foods\RecipeMethod;
use App\Models\Tags\Tag;
use App\Models\Foods\Food;
use App\Models\Units\Unit;
use App\Models\Foods\Entry;

class RecipesController extends Controller {

	/**
	 * select
	 */

	public function filterRecipes(Request $request)
	{
		$typing = $request->get('typing');
		$tag_ids = $request->get('tag_ids');
		return Recipe::filterRecipes($typing, $tag_ids);
	}
	
	/**
	 * Get recipe contents and steps. Change name of method.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function getRecipeContents(Request $request)
	{
		$recipe = Recipe::find($request->get('recipe_id'));
		return Recipe::getRecipeInfo($recipe);
	}

	/**
	 * insert
	 */

	/**
	 * Delete all tags from the recipe then adds the correct tags to it
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function insertTagsIntoRecipe(Request $request)
	{
		$recipe = Recipe::find($request->get('recipe_id'));
		$tag_ids = $request->get('tags');

		//Delete all the tags from the recipe
		DB::table('taggables')->where('taggable_id', $recipe->id)->where('taggable_type', 'recipe')->delete();
		//Insert the correct tags for the recipe
		Recipe::insertTagsIntoRecipe($recipe, $tag_ids);

		return Recipe::filterRecipes('', []);
	}

	public function insertRecipeEntry(Request $request)
	{
		$date = $request->get('date');
		$recipe_id = $request->get('recipe_id');
		$recipe_contents = $request->get('recipe_contents');

		Entry::insertRecipeEntry($date, $recipe_id, $recipe_contents);
		return Entry::getFoodEntries($date);
	}

	public function insertRecipe(Request $request)
	{
		$name = $request->get('name');
		Recipe::insertRecipe($name);
		return Recipe::filterRecipes('', []);
	}

	public function insertRecipeMethod(Request $request)
	{
		$recipe = Recipe::find($request->get('recipe_id'));
		$steps = $request->get('steps');
		
		RecipeMethod::insertRecipeMethod($recipe, $steps);
		
		return Recipe::getRecipeInfo($recipe);
	}

	public function insertFoodIntoRecipe(Request $request)
	{
		$data = $request->all();
		$recipe = Recipe::find($data['recipe_id']);
		
		Recipe::insertFoodIntoRecipe($recipe, $data);
		return Recipe::getRecipeInfo($recipe);
	}

	/**
	 * update
	 */
	
	public function updateRecipeMethod(Request $request)
	{
		$recipe = Recipe::find($request->get('recipe_id'));
		$steps = $request->get('steps');

		//delete the existing method before adding the updated method
		RecipeMethod::deleteRecipeMethod($recipe);
		RecipeMethod::insertRecipeMethod($recipe, $steps);
		
		return Recipe::getRecipeInfo($recipe);
	}

	/**
	 * delete
	 */
	
	public function deleteRecipe(Request $request)
	{
		$id = $request->get('id');
		Recipe::deleteRecipe($id);
		return Recipe::filterRecipes('', []);
	}
	
	public function deleteFoodFromRecipe(Request $request)
	{
		$food_id = $request->get('food_id');
		$recipe = Recipe::find($request->get('recipe_id'));
		// Debugbar::info('recipe', $recipe);
		// Debugbar::info('recipe->id: ' . $recipe->id);
		// Debugbar::info('food_recipe_id: ' . $food_recipe_id);

		$recipe->foods()->detach($food_id);
		return Recipe::getRecipeInfo($recipe);
	}

	public function deleteRecipeEntry(Request $request)
	{
		$date = $request->get('date');
		$recipe_id = $request->get('recipe_id');
		Entry::deleteRecipeEntry($date, $recipe_id);
		
		$response = array(
			"food_entries" => Entry::getFoodEntries($date),
			"calories_for_the_day" => number_format(Food::getCaloriesForDay($date), 2),
			"calories_for_the_week" => number_format(Food::getCaloriesFor7Days($date), 2)
		);
		return $response;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

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