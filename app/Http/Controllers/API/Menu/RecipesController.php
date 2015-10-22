<?php namespace App\Http\Controllers\API\Recipes;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Menu\Recipe;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;


/**
 * Class RecipesController
 * @package App\Http\Controllers\Recipes
 */
class RecipesController extends Controller
{

    /**
     *
     * @param Request $request
     * @return array
     */
    public function filterRecipes(Request $request)
    {
        $typing = $request->get('typing');
        $tag_ids = $request->get('tag_ids');

        return Recipe::filterRecipes($typing, $tag_ids);
    }

    /**
     * Get recipe contents and steps. Change name of method.
     * @param Request $request
     * @return array
     */
    public function getRecipeContents(Request $request)
    {
        $recipe = Recipe::find($request->get('recipe_id'));

        return Recipe::getRecipeInfo($recipe);
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $recipe = new Recipe([
            'name' => $request->get('name')
        ]);
        $recipe->user()->associate(Auth::user());
        $recipe->save();

        return $this->responseCreated($recipe);
    }

    /**
     *
     * @param Recipe $recipe
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return $this->responseNoContent();
    }
}