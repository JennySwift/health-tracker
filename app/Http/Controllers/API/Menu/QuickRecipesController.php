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
     *
     * @param Request $request
     * @return array
     */
    public function checkForSimilarNames(Request $request)
    {
        return $this->quickRecipesRepository->checkEntireRecipeForSimilarNames($request->get('ingredients'));
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $recipe = $this->recipesRepository->insert($request->get('name'), $request->get('ingredients'), $request->get('steps'));

        return $this->responseCreatedWithTransformer($recipe, new RecipeTransformer);
    }

}