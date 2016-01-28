<?php namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\RecipeTransformer;
use App\Http\Transformers\RecipeWithIngredientsTransformer;
use App\Models\Menu\Recipe;
use App\Models\Menu\RecipeMethod;
use App\Models\Tags\Tag;
use App\Repositories\RecipesRepository;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * Class RecipesController
 * @package App\Http\Controllers\Recipes
 */
class RecipesController extends Controller
{
    /**
     * @var RecipesRepository
     */
    private $recipesRepository;

    /**
     * @param RecipesRepository $recipesRepository
     */
    public function __construct(RecipesRepository $recipesRepository)
    {
        $this->recipesRepository = $recipesRepository;
    }

    /**
     *
     * @return array
     */
    public function index()
    {
        return $this->recipesRepository->filterRecipes('', []);
    }

    /**
     *
     * @param Request $request
     * @return array
     */
    public function filterRecipes(Request $request)
    {
        return $this->recipesRepository->filterRecipes($request->get('typing'), $request->get('tag_ids'));
    }

    /**
     * Get recipe contents and steps, for the recipe popup
     * @param Recipe $recipe
     * @return array
     */
    public function show(Recipe $recipe)
    {
        return $this->recipesRepository->getRecipeInfo($recipe);
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $recipe = $this->recipesRepository->insert($request->get('name'));

        return $this->responseCreatedWithTransformer($recipe, new RecipeTransformer);
    }

    /**
    * UPDATE /api/recipes/{recipes}
    * @param Request $request
    * @param Recipe $recipe
    * @return Response
    */
    public function update(Request $request, Recipe $recipe)
    {
        if ($request->get('removeIngredient')) {
            //This line didn't work so I'm using DB::table instead
//            $recipe->foods()->detach([$request->get('food_id') => ['unit_id' => $request->get('unit_id')]]);
            DB::table('food_recipe')
                ->where('food_id', $request->get('food_id'))
                ->where('unit_id', $request->get('unit_id'))
                ->delete();
        }
        else {
            // Create an array with the new fields merged
            $data = array_compare($recipe->toArray(), $request->only([
                'name'
            ]));

            $recipe->update($data);

            if ($request->has('tag_ids')) {
                $ids = $request->get('tag_ids');
                $pivotData = array_fill(0, count($ids), ['taggable_type' => 'recipe']);
                $syncData  = array_combine($ids, $pivotData);
                $recipe->tags()->sync($syncData);
            }

            if ($request->has('steps')) {
                //Remove the existing steps from the recipe
                $currentSteps = $recipe->steps()->delete();

                //Attach the updated steps to the recipe
                $count = 0;
                foreach ($request->get('steps') as $step) {
                    $count++;
                    $step = new RecipeMethod([
                        'step' => $count,
                        'text' => $step
                    ]);
                    $step->user()->associate(Auth::user());
                    $step->recipe()->associate($recipe);
                    $step->save();
                }
            }

            if ($request->has('ingredients')) {
                //Remove the existing ingredients from the recipe
                $currentFoodIds = $recipe->foods()->lists('id')->all();
                $recipe->foods()->detach($currentFoodIds);

                //Attach the updated ingredients to the recipe
                foreach ($request->get('ingredients') as $ingredient) {
                    $recipe->foods()->attach([$ingredient['food_id'] => ['unit_id' => $ingredient['unit_id']]]);
                }
            }
        }

        $recipe = $this->transform($this->createItem($recipe, new RecipeWithIngredientsTransformer))['data'];
        return response($recipe, Response::HTTP_OK);
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