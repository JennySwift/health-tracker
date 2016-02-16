<?php

namespace App\Repositories;

use App\Http\Transformers\Menu\RecipeWithIngredientsTransformer;
use App\Http\Transformers\Menu\RecipeTransformer;
use App\Http\Transformers\UnitTransformer;
use App\Models\Menu\Food;
use App\Models\Menu\Recipe;
use App\Models\Menu\RecipeMethod;
use App\Models\Units\Unit;
use Auth;
use DB;

/**
 * Class RecipesRepository
 * @package App\Repositories
 */
class RecipesRepository {
    /**
     * @var FoodsRepository
     */
    private $foodsRepository;
    /**
     * @var UnitsRepository
     */
    private $unitsRepository;

    /**
     * @param FoodsRepository $foodsRepository
     * @param UnitsRepository $unitsRepository
     */
    public function __construct(FoodsRepository $foodsRepository, UnitsRepository $unitsRepository)
    {
        $this->foodsRepository = $foodsRepository;
        $this->unitsRepository = $unitsRepository;
    }
    /**
     *
     * @param $name
     * @param bool $ingredients
     * @param bool $steps
     * @return Recipe
     */
    public function insert($name, $ingredients = false, $steps = false)
    {
        $recipe = new Recipe([
            'name' => $name
        ]);
        $recipe->user()->associate(Auth::user());
        $recipe->save();

        if ($steps) {
            $recipe = $this->insertRecipeMethod($recipe, $steps);
        }

        if ($ingredients) {
            $ingredients = $this->insertFoodAndUnitIfNotExist($ingredients);
            $recipe = $this->insertFoodsIntoRecipe($recipe, $ingredients);
        }

        return $recipe;
    }

    /**
     * @VP:
     * Is this how I'm supposed to modify an array within a foreach loop?
     * @param $ingredients
     * @return mixed
     */
    private function insertFoodAndUnitIfNotExist($ingredients)
    {
        foreach ($ingredients as $key => $ingredient) {
            $ingredients[$key]['food'] = $this->findOrInsertFoodIfNotExists($ingredient['food']);
            $ingredients[$key]['unit'] = $this->findOrInsertUnitIfNotExists($ingredient['unit']);
        }
        return $ingredients;
    }

    /**
     *
     * @param $name
     * @return Food
     */
    public function findOrInsertFoodIfNotExists($name)
    {
        $food = Food::forCurrentUser()
            ->where('name', $name)
            ->first();

        if (!$food) {
            $food = $this->foodsRepository->insert($name);
        }

        return $food;
    }

    /**
     *
     * @param $name
     * @return Unit
     */
    public function findOrInsertUnitIfNotExists($name)
    {
        $unit = Unit::forCurrentUser()
            ->where('name', $name)
            ->first();

        if (!$unit) {
            $unit = $this->unitsRepository->insert($name);
        }

        return $unit;
    }

    /**
     *
     * @param $recipe
     * @param $ingredients
     * @return mixed
     */
    private function insertFoodsIntoRecipe($recipe, $ingredients)
    {
        foreach ($ingredients as $ingredient) {
            $food = $ingredient['food'];
            $unit = $ingredient['unit'];

            $this->insertFoodIntoRecipe($recipe, $ingredient);

            //Attach the unit to the food if it doesn't already belong to the food
            if ($food->units()->find($unit->id) === 0) {
                $food->units()->attach($unit->id);
            }
        }

        return $recipe;
    }

    /**
     * How do I attach the unit properly here, since there are three
     * foreign keys in the food_recipe pivot table?
     *
     * Use sync and pass the unit id in there, as if it was just a field and not
     * a relationship.
     * $user->roles()->sync(array($food_id => array('unit_id' => 1)));
     *
     * But what I have done is fine for just inserting one food at a time
     * rather than all of them at once.
     * @param $recipe
     * @param $data
     */
    public function insertFoodIntoRecipe($recipe, $data)
    {
        $description = isset($data['description']) ? $data['description'] : '';

        $recipe->foods()->attach($data['food']->id, [
            'quantity' => $data['quantity'],
            'description' => $description,
            'unit_id' => $data['unit']->id
        ]);

        $recipe->save();
    }


    /**
     * Get all recipes, along with their tags, for the user, and that match the $name filter
     * @param $name
     * @param $tag_ids
     * @return array
     */
    public function filterRecipes($name, $tag_ids)
    {
        $recipes = Recipe::forCurrentUser();

        //filter by name
        if ($name !== '') {
            $name = '%' . $name . '%';

            $recipes = $recipes
                ->where('name', 'LIKE', $name);
        }

        //filter by tags
        if (count($tag_ids) > 0) {
            foreach ($tag_ids as $tag_id) {
                $recipes = $recipes->whereHas('tags', function ($q) use ($tag_id) {
                    $q->where('tags.id', $tag_id);
                });
            }
        }

        $recipes = $recipes->with('tags')
            ->orderBy('name', 'asc')
            ->get();

        return transform(createCollection($recipes, new RecipeTransformer))['data'];
    }

    /**
     * Get recipe contents and steps.
     * Contents should include the foods that belong to the recipe,
     * along with the description, quantity, and unit
     * for the food when used in the recipe (from food_recipe table),
     * and with the tags for the recipe.
     * Redoing after refactor. Still need description, quantity, unit.
     * @param $recipe
     * @return array
     */
    public function getRecipeInfo($recipe)
    {
        $recipe = transform(createItem($recipe, new RecipeWithIngredientsTransformer))['data'];

        //For some reason the units for each food aren't being added to the food
        //from my IngredientTransformer, so add them here
        foreach ($recipe['ingredients']['data'] as $index => $ingredient) {
            $units = Food::find($ingredient['food']['data']['id'])->units;
            $units = transform(createCollection($units, new UnitTransformer));
            $recipe['ingredients']['data'][$index]['food']['data']['units'] = $units;
        }

        return $recipe;
    }

    /**
     *
     * @param $recipe
     * @param $steps
     */
    public function insertRecipeMethod($recipe, $steps)
    {
        $step_number = 0;
        foreach ($steps as $step_text) {
            $step_number++;

            $method = new RecipeMethod([
                'step' => $step_number,
                'text' => $step_text
            ]);

            $method->user()->associate(Auth::user());
            $method->recipe()->associate($recipe);
            $method->save();
        }

        return $recipe;
    }


}