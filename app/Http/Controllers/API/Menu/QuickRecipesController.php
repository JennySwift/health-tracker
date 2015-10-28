<?php namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Menu\Food;
use App\Models\Menu\Recipe;
use App\Models\Menu\RecipeMethod;
use App\Models\Units\Unit;
use App\Repositories\FoodsRepository;
use App\Repositories\QuickRecipesRepository;
use App\Repositories\RecipesRepository;
use App\Repositories\UnitsRepository;
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
     * @param QuickRecipesRepository $quickRecipesRepository
     */
    public function __construct(QuickRecipesRepository $quickRecipesRepository)
    {
        $this->quickRecipesRepository = $quickRecipesRepository;
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
        $data = $request->get('recipe');

        if ($request->get('check_for_similar_names')) {
            $similarNames = $this->quickRecipesRepository->checkEntireRecipeForSimilarNames($data['items']);

            if (isset($similarNames['foods']) || isset($similarNames['units'])) {
                return [
                    'similar_names' => $similarNames
                ];
            }
            else {
                //No similar names were found.
                //Insert the recipe.
                $data['items'] = $this->quickRecipesRepository->populateArrayBeforeInserting($data['items']);
                return $this->quickRecipesRepository->insertEverything($data);
            }
        }
        else {
            //We are not checking for similar names.
            //Insert the recipe
            $data['items'] = $this->quickRecipesRepository->populateArrayBeforeInserting($data['items']);
            return $this->quickRecipesRepository->insertEverything($data);
        }
    }



}