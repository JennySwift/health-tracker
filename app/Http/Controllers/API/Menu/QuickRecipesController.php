<?php namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\RecipeTransformer;
use App\Http\Transformers\RecipeWithIngredientsTransformer;
use App\Repositories\QuickRecipesRepository;
use App\Repositories\RecipesRepository;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;


/**
 * Class QuickRecipesController
 * @package App\Http\Controllers\Recipes
 */
class QuickRecipesController extends Controller
{
    /**
     * @var QuickRecipesRepository
     */
    private $quickRecipesRepository;
    /**
     * @var RecipesRepository
     */
    private $recipesRepository;

    /**
     * @param QuickRecipesRepository $quickRecipesRepository
     * @param RecipesRepository $recipesRepository
     */
    public function __construct(QuickRecipesRepository $quickRecipesRepository, RecipesRepository $recipesRepository)
    {
        $this->quickRecipesRepository = $quickRecipesRepository;
        $this->recipesRepository = $recipesRepository;
    }

    /**
     * Check for similar names.
     * If they are found, return them.
     * If not, insert the recipe.
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $ingredients = $request->get('ingredients');

        if ($request->get('check_for_similar_names')) {
            $similarNames = $this->quickRecipesRepository->checkEntireRecipeForSimilarNames($ingredients);

            if (isset($similarNames['foods']) || isset($similarNames['units'])) {
                return [
                    'similar_names' => $similarNames
                ];
            }
            else {
                //No similar names were found.
                //Insert the recipe.
                $recipe = $this->recipesRepository->insert($request->get('name'), $ingredients, $request->get('steps'));
            }
        }
        else {
            //We are not checking for similar names.
            //Insert the recipe
            $recipe = $this->recipesRepository->insert($request->get('name'), $ingredients, $request->get('steps'));
        }
        return $this->responseCreatedWithTransformer($recipe, new RecipeTransformer);
    }



}