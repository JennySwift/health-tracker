<?php namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Menu\Recipe;
use App\Models\Menu\RecipeMethod;
use Auth;
use Illuminate\Http\Request;

/**
 * Class RecipeMethodsController
 * @package App\Http\Controllers\Recipes
 */
class RecipeMethodsController extends Controller
{
    /**
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $recipe = Recipe::find($request->get('recipe_id'));
        $steps = $request->get('steps');

        $method = RecipeMethod::insertRecipeMethod($recipe, $steps);

        return $this->responseCreated($method);
    }

    /**
     * Delete the existing method before adding the updated method
     * @param Request $request
     * @return array
     */
    public function updateRecipeMethod(Request $request)
    {
        $recipe = Recipe::find($request->get('recipe_id'));
        $steps = $request->get('steps');

        RecipeMethod::deleteRecipeMethod($recipe);
        RecipeMethod::insertRecipeMethod($recipe, $steps);

        return Recipe::getRecipeInfo($recipe);
    }
}
