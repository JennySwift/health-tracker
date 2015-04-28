<?php namespace App\Http\Controllers\Recipes;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Foods\Recipe;
use App\Models\Foods\FoodRecipe;
use App\Models\Foods\RecipeMethod;
use App\Models\Foods\RecipeTag;
use App\Models\Foods\Food;
use App\Models\Foods\FoodUnit;
use App\Models\Foods\FoodEntry;
use DB;
use Auth;
use Debugbar;

class RecipesController extends Controller {

	/**
	 * select
	 */
	
	public function filterRecipes (Request $request) {
		include(app_path() . '/inc/functions.php');
		$typing = $request->get('typing');
		$tag_ids = $request->get('tag_ids');
		return filterRecipes($typing, $tag_ids);
	}
	
	public function getRecipeContents (Request $request) {
		$recipe_id = $request->get('recipe_id');

		return array(
			'contents' => FoodRecipe::getRecipeContents($recipe_id),
			'steps' => RecipeMethod::getRecipeSteps($recipe_id)
		);
	}

	/**
	 * insert
	 */

	public function insertTagsIntoRecipe (Request $request) {
		//deletes all tags then adds the correct tags
		include(app_path() . '/inc/functions.php');
		$recipe_id = $request->get('recipe_id');
		$tags = $request->get('tags');
		
		RecipeTag::deleteTagsFromRecipe($recipe_id);
		RecipeTag::insertTagsIntoRecipe($recipe_id, $tags);

		return filterRecipes('', []);
	}

	public function insertQuickRecipe (Request $request) {
		include(app_path() . '/inc/functions.php');
		$recipe_name = $request->get('recipe_name');
		$contents = $request->get('contents');
		$steps = $request->get('steps');
		$check_similar_names = $request->get('check_similar_names');
		
		$similar_names = insertQuickRecipe($recipe_name, $contents, $steps, $check_similar_names);

		if ($similar_names) {
			return array(
				'similar_names' => $similar_names
			);
		}
		else {
			return array(
				'recipes' => filterRecipes('', []),
				'foods_with_units' => Food::getAllFoodsWithUnits(),
				'food_units' => FoodUnit::getFoodUnits()
			);
		}	
	}

	public function insertRecipeEntry (Request $request) {
		$date = $request->get('date');
		$recipe_id = $request->get('recipe_id');
		$recipe_contents = $request->get('recipe_contents');

		FoodEntry::insertRecipeEntry($date, $recipe_id, $recipe_contents);
		return FoodEntry::getFoodEntries($date);
	}

	public function insertRecipe (Request $request) {
		include(app_path() . '/inc/functions.php');
		$name = $request->get('name');
		Recipe::insertRecipe($name);
		return filterRecipes('', []);
	}

	public function insertRecipeMethod (Request $request) {
		$recipe_id = $request->get('recipe_id');
		$steps = $request->get('steps');
		
		RecipeMethod::insertRecipeMethod($recipe_id, $steps);
		
		return array(
			'contents' => FoodRecipe::getRecipeContents($recipe_id),
			'steps' => RecipeMethod::getRecipeSteps($recipe_id)
		);
	}

	public function insertFoodIntoRecipe (Request $request) {
		$data = $request->all();
		$recipe_id = $data['recipe_id'];
		
		FoodRecipe::insertFoodIntoRecipe($recipe_id, $data);
		return FoodRecipe::getRecipeContents($recipe_id);
	}

	/**
	 * update
	 */
	
	public function updateRecipeMethod (Request $request) {
		include(app_path() . '/inc/functions.php');
		$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];
		$steps = json_decode(file_get_contents('php://input'), true)["steps"];

		//delete the existing method before adding the updated method
		deleteRecipeMethod($recipe_id);
		insertRecipeMethod($recipe_id, $steps);
		
		return array(
			'contents' => getRecipeContents($recipe_id),
			'steps' => getRecipeSteps($recipe_id)
		);
	}

	/**
	 * delete
	 */
	
	public function deleteRecipe (Request $request) {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		deleteRecipe($id);
		return filterRecipes('', []);
	}
	
	public function deleteFoodFromRecipe (Request $request) {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];
		deleteFoodFromRecipe($id);
		return getRecipeContents($recipe_id);
	}

	public function deleteRecipeTag (Request $request) {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		deleteRecipeTag($id);
		return getRecipeTags();
	}

	public function deleteRecipeEntry (Request $request) {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];
		deleteRecipeEntry($date, $recipe_id);
		
		$response = array(
			"food_entries" => getFoodEntries($date),
			"calories_for_the_day" => number_format(getCaloriesForTimePeriod($date, "day"), 2),
			"calories_for_the_week" => number_format(getCaloriesForTimePeriod($date, "week"), 2)
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
