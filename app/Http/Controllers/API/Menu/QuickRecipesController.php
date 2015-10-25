<?php namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Menu\Food;
use App\Models\Menu\Recipe;
use App\Models\Menu\RecipeMethod;
use App\Models\Units\Unit;
use App\Repositories\FoodsRepository;
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
     * @var RecipesRepository
     */
    private $recipesRepository;
    /**
     * @var UnitsRepository
     */
    private $unitsRepository;
    /**
     * @var FoodsRepository
     */
    private $foodsRepository;

    /**
     * @param RecipesRepository $recipesRepository
     * @param UnitsRepository $unitsRepository
     * @param FoodsRepository $foodsRepository
     */
    public function __construct(RecipesRepository $recipesRepository, UnitsRepository $unitsRepository, FoodsRepository $foodsRepository)
    {
        $this->recipesRepository = $recipesRepository;
        $this->unitsRepository = $unitsRepository;
        $this->foodsRepository = $foodsRepository;
    }

    /**
     * This is the function that is called from the ajax request.
     * Check for similar names.
     * If they are found, return them.
     * If not, insert the recipe.
     * @param Request $request
     * @return array
     */
    public function quickRecipe(Request $request)
    {
        $data = $request->get('recipe');

        if ($request->get('check_similar_names')) {
            $similarNames = $this->checkEntireRecipeForSimilarNames($data['items']);

            if (isset($similarNames['foods']) || isset($similarNames['units'])) {
                return [
                    'similar_names' => $similarNames
                ];
            }
            else {
                //No similar names were found.
                //Insert the recipe.
                $data['items'] = $this->populateArrayBeforeInserting($data['items']);
                return $this->insertEverything($data);
            }
        }
        else {
            //We are not checking for similar names.
            //Insert the recipe
            $data['items'] = $this->populateArrayBeforeInserting($data['items']);
            return $this->insertEverything($data);
        }
    }

    /**
     *
     * @param $contents
     * @return array
     */
    private function checkEntireRecipeForSimilarNames($contents)
    {
        //$index is so if a similar name is found,
        //I know what index it is in the quick recipe array for the javascript.
        $index = -1;
        $similarNames = [];

        foreach ($contents as $item) {
            $index++;

            $similarFoodInfo = $this->populateSimilarFoodNames($item['food'], $index);

            if (count($similarFoodInfo) > 0) {
                $similarNames['foods'][] = $similarFoodInfo;
            }

            $similarUnitInfo = $this->populateSimilarUnitNames($item['unit'], $index);

            if (count($similarUnitInfo) > 0) {
                $similarNames['units'][] = $similarUnitInfo;
            }
        }

        return $similarNames;
    }

    /**
     *
     * @param $unitName
     * @param $index
     * @return array
     */
    private function populateSimilarUnitNames($unitName, $index)
    {
        $foundUnit = $this->checkSimilarNames($unitName, 'units');

        if ($foundUnit) {
            return [
                'specified_unit' => ['name' => $unitName],
                'existing_unit' => ['name' => $foundUnit],
                'checked' => $foundUnit,
                'index' => $index
            ];
        }

        return [];
    }

    /**
     *
     * @param $foodName
     * @param $index
     * @return array
     */
    private function populateSimilarFoodNames($foodName, $index)
    {
        $foundFood = $this->checkSimilarNames($foodName, 'foods');

        if ($foundFood) {
            return [
                'specified_food' => ['name' => $foodName],
                'existing_food' => ['name' => $foundFood],
                'checked' => $foundFood,
                'index' => $index
            ];
        }

        return [];
    }

    /**
     * We can insert things now that either no similar names were found,
     * or we have already checked for similar names previously.
     * Add the item to the array for inserting later,
     * when all items have been added to the array
     * @param $contents
     * @return array
     */
    private function populateArrayBeforeInserting($contents)
    {
        $data_to_insert = [];

        foreach ($contents as $item) {
            $data_to_insert[] = [
                'food_id' => $this->foodsRepository->insertFoodIfNotExists($item['food'])->id,
                'unit_id' => $this->unitsRepository->insertUnitIfNotExists($item['unit'])->id,
                'quantity' => $item['quantity'],
                'description' => $item['description']
            ];
        }

        return $data_to_insert;
    }

    /**
     *
     * @param $data
     * @return array
     */
    private function insertEverything($data)
    {
        $recipe = $this->insertRecipe($data['name']);
        $this->recipesRepository->insertRecipeMethod($recipe, $data['steps']);

        //insert the items into food_recipe table
        foreach ($data['items'] as $item) {
            $food = Food::find($item['food_id']);
            $unit = Unit::find($item['unit_id']);

            $this->insertFoodIntoRecipe($recipe, $item);

            //Attach the unit to the food if it doesn't already belong to the food
            if ($food->units()->find($unit->id) === 0) {
                $food->units()->attach($unit->id);
            }
        }

        return $this->recipesRepository->filterRecipes('', []);
    }

    /**
     * @param $recipe
     * @param $data
     */
    public function insertFoodIntoRecipe($recipe, $data)
    {
        $recipe->unit()->associate(Unit::find($data['unit_id']));

        $recipe->foods()->attach($data['food_id'], [
            'quantity' => $data['quantity'],
            'description' => $data['description'],
        ]);

        $recipe->save();
    }


    /**
     * Insert recipe into recipes table and retrieve the id
     * @param $name
     * @return mixed
     */
    private function insertRecipe($name)
    {
        $recipe = new Recipe([
            'name' => $name
        ]);

        $recipe->user()->associate(Auth::user());
        $recipe->save();

        return $recipe;
    }

    /**
     *
     * @param $table
     * @param $name
     * @return mixed
     */
    private function countItem($table, $name)
    {
        $count = DB::table($table)
            ->where('name', $name)
            ->where('user_id', Auth::user()->id)
            ->count();

        return $count;
    }

    /**
     *
     * @param $name
     * @param $table
     * @return mixed
     */
    private function pluckName($name, $table)
    {
        $name = DB::table($table)
            ->where('name', $name)
            ->where('user_id', Auth::user()->id)
            ->pluck('name');

        return $name;
    }

    /**
     * Currently this checks the units table for similar names.
     * I should change it to find them only if the unit type is for food.
     * @param $name
     * @param $table
     * @return mixed
     */
    private function checkSimilarNames($name, $table)
    {
        $count = $this->countItem($table, $name);

        if ($count < 1) {
            //the name does not exist

            if (substr($name, -3) === 'ies') {
                //the name ends in 'ies'. check if it's singular form exists.
                $similar_name = substr($name, 0, -3) . 'y';
                $found = $this->pluckName($similar_name, $table);
            }
            elseif (substr($name, -1) === 'y') {
                //the name ends in 'y'. Check if it's plural form exists.
                $similar_name = substr($name, 0, -1) . 'ies';
                $found = $this->pluckName($similar_name, $table);
            }

            elseif (substr($name, -1) === 's' && !isset($found)) {
                //the name ends in s. check if its singular form is in the database
                $similar_name = substr($name, 0, -1);
                $found = $this->pluckName($similar_name, $table);

                //if nothing was found, check if its plural form is in the database
                if (!isset($found)) {
                    $similar_name = $name . 'es';
                    $found = $this->pluckName($similar_name, $table);
                }
            }

            //check if it's plural form exists if no singular forms were found
            if (!isset($found)) {
                $similar_name = $name . 's';
                $found = $this->pluckName($similar_name, $table);
            }
        }
        if (isset($found)) {
            return $found;
        }
	}

}