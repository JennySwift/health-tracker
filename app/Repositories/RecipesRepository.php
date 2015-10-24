<?php

namespace App\Repositories;

use App\Models\Menu\Recipe;
use App\Models\Menu\RecipeMethod;
use Auth;

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

        $recipes = $recipes
            ->with('tags')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        $array = array();
        foreach ($recipes as $recipe) {
            $id = $recipe->id;
            $name = $recipe->name;
            $tags = $this->getRecipeTags($id);

            $array[] = array(
                "id" => $id,
                "name" => $name,
                "tags" => $tags
            );
        }

        return $array;
    }

    /**
     * Get recipe contents and steps.
     * Contents should include the foods that belong to the recipe,
     * along with the description, quantity, and unit for the food when used in the recipe (from food_recipe table),
     * and with the tags for the recipe.
     * Redoing after refactor. Still need description, quantity, unit.
     * @param $recipe
     * @return array
     */
    public function getRecipeInfo($recipe)
    {
        /**
         * @VP:
         * Why can't I do:
         * $recipe_info = static::where('id', $recipe->id)
         * ->with('foods')
         * ->with('steps')
         * ->get();
         * and then dd($recipe_info->foods)?
         * It gives an error.
         */

        $contents = DB::table('food_recipe')
            ->where('recipe_id', $recipe->id)
            ->join('foods', 'food_id', '=', 'foods.id')
            ->join('units', 'unit_id', '=', 'units.id')
            ->select('foods.id as food_id', 'foods.name', 'units.name as unit_name', 'units.id as unit_id', 'quantity', 'description')
            ->get();

        //Add the units to all the foods in $contents, for the temporary recipe popup
        $contents_with_units = [];
        foreach ($contents as $ingredient) {
            $food_id = $ingredient->food_id;
            $food = Food::find($food_id);
            $ingredient->units = $food->units;
            $contents_with_units[] = $ingredient;
        }

        return [
            'recipe' => $recipe,
            'contents' => $contents_with_units,
            'steps' => $recipe->steps,
            'tags' => $recipe->tags->lists('id')
        ];
    }

    /**
     * This probably needs doing after refactor
     * @param $recipe
     * @param $data
     */
    public function insertFoodIntoRecipe($recipe, $data)
    {
        if (isset($data['description'])) {
            $description = $data['description'];
        }
        else {
            $description = null;
        }

        DB::table('food_recipe')
            ->insert([
                'recipe_id' => $recipe->id,
                'food_id' => $data['food_id'],
                'unit_id' => $data['unit_id'],
                'quantity' => $data['quantity'],
                'description' => $description,
                'user_id' => Auth::user()->id
            ]);
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