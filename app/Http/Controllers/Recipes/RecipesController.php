<?php namespace App\Http\Controllers\Recipes;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
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
	
	public function getRecipeContents(Request $request)
	{
		$recipe_id = $request->get('recipe_id');

		return array(
			'contents' => FoodRecipe::getRecipeContents($recipe_id),
			'steps' => RecipeMethod::getRecipeSteps($recipe_id)
		);
	}

	/**
	 * insert
	 */

	public function insertTagsIntoRecipe(Request $request)
	{
		//deletes all tags then adds the correct tags
		$recipe_id = $request->get('recipe_id');
		$tags = $request->get('tags');
		
		Tag::deleteTagsFromRecipe($recipe_id);
		Tag::insertTagsIntoRecipe($recipe_id, $tags);

		return Recipe::filterRecipes('', []);
	}

	public function insertQuickRecipe(Request $request)
	{
		$recipe_name = $request->get('recipe_name');
		$contents = $request->get('contents');
		$steps = $request->get('steps');
		$check_similar_names = $request->get('check_similar_names');
		
		$similar_names = Recipe::insertQuickRecipe($recipe_name, $contents, $steps, $check_similar_names);

		if ($similar_names) {
			return array(
				'similar_names' => $similar_names
			);
		}
		else {
			return array(
				'recipes' => Recipe::filterRecipes('', []),
				'foods_with_units' => Food::getAllFoodsWithUnits(),
				'food_units' => Unit::getFoodUnits()
			);
		}	
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
		$recipe_id = $request->get('recipe_id');
		$steps = $request->get('steps');
		
		RecipeMethod::insertRecipeMethod($recipe_id, $steps);
		
		return array(
			'contents' => FoodRecipe::getRecipeContents($recipe_id),
			'steps' => RecipeMethod::getRecipeSteps($recipe_id)
		);
	}

	public function insertFoodIntoRecipe(Request $request)
	{
		$data = $request->all();
		$recipe_id = $data['recipe_id'];
		
		FoodRecipe::insertFoodIntoRecipe($recipe_id, $data);
		return FoodRecipe::getRecipeContents($recipe_id);
	}

	/**
	 * update
	 */
	
	public function updateRecipeMethod(Request $request)
	{
		$recipe_id = $request->get('recipe_id');
		$steps = $request->get('steps');

		//delete the existing method before adding the updated method
		RecipeMethod::deleteRecipeMethod($recipe_id);
		RecipeMethod::insertRecipeMethod($recipe_id, $steps);
		
		return array(
			'contents' => FoodRecipe::getRecipeContents($recipe_id),
			'steps' => RecipeMethod::getRecipeSteps($recipe_id)
		);
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
		$food_id = $request->get('id');
		$recipe_id = $request->get('recipe_id');
		$recipe = Recipe::find($request->get('recipe_id'));
		$recipe->foods()->detach($food_id);
		return FoodRecipe::getRecipeContents($recipe_id);
	}

	public function deleteRecipeTag(Request $request)
	{
		$id = $request->get('id');
		Tag::deleteRecipeTag($id);
		return Recipe::getRecipeTags();
	}

	public function deleteRecipeEntry(Request $request)
	{
		$date = $request->get('date');
		$recipe_id = $request->get('recipe_id');
		FoodRecipe::deleteRecipeEntry($date, $recipe_id);
		
		$response = array(
			"food_entries" => Entry::getFoodEntries($date),
			"calories_for_the_day" => number_format(Food::getCaloriesForTimePeriod($date, "day"), 2),
			"calories_for_the_week" => number_format(Food::getCaloriesForTimePeriod($date, "week"), 2)
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
