<?php namespace App\Http\Controllers\Recipes;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Recipe;
use DB;
use Auth;
use Debugbar;

class RecipesController extends Controller {

	/**
	 * select
	 */
	
	public function filterRecipes () {
		include(app_path() . '/inc/functions.php');
		$typing = json_decode(file_get_contents('php://input'), true)["typing"];
		$tag_ids = json_decode(file_get_contents('php://input'), true)["tag_ids"];
		return filterRecipes($typing, $tag_ids);
	}
	
	public function getRecipeContents () {
		include(app_path() . '/inc/functions.php');
		$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];

		return array(
			'contents' => getRecipeContents($recipe_id),
			'steps' => getRecipeSteps($recipe_id)
		);
	}

	/**
	 * insert
	 */

	public function insertRecipeTag () {
		//creates a new recipe tag
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		insertRecipeTag($name);
		return getRecipeTags();
	}

	public function insertTagsIntoRecipe () {
		//deletes all tags then adds the correct tags
		include(app_path() . '/inc/functions.php');
		$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];
		$tags = json_decode(file_get_contents('php://input'), true)["tags"];
		deleteTagsFromRecipe($recipe_id);
		insertTagsIntoRecipe($recipe_id, $tags);
		return filterRecipes('', []);
	}

	public function insertQuickRecipe () {
		include(app_path() . '/inc/functions.php');
		$recipe_name = json_decode(file_get_contents('php://input'), true)["recipe_name"];
		$contents = json_decode(file_get_contents('php://input'), true)["contents"];
		$steps = json_decode(file_get_contents('php://input'), true)["steps"];
		$check_similar_names = json_decode(file_get_contents('php://input'), true)["check_similar_names"];
		
		$similar_names = insertQuickRecipe($recipe_name, $contents, $steps, $check_similar_names);

		if ($similar_names) {
			return array(
				'similar_names' => $similar_names
			);
		}
		else {
			return array(
				'recipes' => filterRecipes('', []),
				'foods_with_units' => getAllFoodsWithUnits(),
				'food_units' => getFoodUnits()
			);
		}	
	}

	public function insertRecipeEntry () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];
		$recipe_contents = json_decode(file_get_contents('php://input'), true)["recipe_contents"];

		insertRecipeEntry($date, $recipe_id, $recipe_contents);
		return getFoodEntries($date);
	}

	public function insertRecipe () {
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		insertRecipe($name);
		return filterRecipes('', []);
	}

	public function insertRecipeMethod () {
		include(app_path() . '/inc/functions.php');
		$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];
		$steps = json_decode(file_get_contents('php://input'), true)["steps"];
		insertRecipeMethod($recipe_id, $steps);
		
		return array(
			'contents' => getRecipeContents($recipe_id),
			'steps' => getRecipeSteps($recipe_id)
		);
	}

	public function insertFoodIntoRecipe () {
		include(app_path() . '/inc/functions.php');
		$data = json_decode(file_get_contents('php://input'), true);
		$recipe_id = $data['recipe_id'];
		
		insertFoodIntoRecipe($recipe_id, $data);
		return getRecipeContents($recipe_id);
	}

	public function insertRecipeEntries () {
		$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
		$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];

		$sql = "INSERT INTO food_recipe (recipe_id, food_id, quantity, unit) VALUES ($recipe_id, $food_id, $quantity, $unit_id);";
	}

	/**
	 * update
	 */
	
	public function updateRecipeMethod () {
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
	
	public function deleteRecipe () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		deleteRecipe($id);
		return filterRecipes('', []);
	}
	
	public function deleteFoodFromRecipe () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];
		deleteFoodFromRecipe($id);
		return getRecipeContents($recipe_id);
	}

	public function deleteRecipeTag () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		deleteRecipeTag($id);
		return getRecipeTags();
	}

	public function deleteRecipeEntry () {
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
