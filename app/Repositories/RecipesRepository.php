<?php

namespace App\Repositories;

use App\Http\Transformers\RecipeTransformer;
use App\Models\Menu\Food;
use App\Models\Menu\Recipe;
use App\Models\Menu\RecipeMethod;
use Auth;
use DB;

/**
 * Class RecipesRepository
 * @package App\Repositories
 */
class RecipesRepository {

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
        $contents = DB::table('food_recipe')
            ->where('recipe_id', $recipe->id)
            ->join('foods', 'food_id', '=', 'foods.id')
            ->join('units', 'unit_id', '=', 'units.id')
            ->select('foods.id as food_id', 'foods.name', 'units.name as unit_name', 'units.id as unit_id', 'quantity', 'description')
            ->get();

        //Add the units to all the foods in $contents, for the temporary recipe popup
        $contents_with_units = [];
        foreach ($contents as $ingredient) {
            $food = Food::find($ingredient->food_id);
            $ingredient->units = $food->units;
            $contents_with_units[] = $ingredient;
        }

        $recipe->steps;

        return [
            'recipe' => $recipe,
            'contents' => $contents_with_units,
            'tags' => $recipe->tags->lists('id')
        ];
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
    }


}