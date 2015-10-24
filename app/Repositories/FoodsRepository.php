<?php

namespace App\Repositories;

use App\Http\Transformers\FoodTransformer;
use App\Models\Menu\Food;
use App\Models\Units\Unit;

/**
 * Class FoodsRepository
 * @package App\Repositories
 */
class FoodsRepository
{

    /**
     * Get all food units that belong to the user,
     * as well as all units that belong to the particular food.
     *
     * For when user clicks on a food in the foods table
     * A popup is displayed, showing all food units
     * with the units for that food checked
     * and the option to set the default unit for the food
     * and the option to set the calories for each of the food's units
     */
    public function getFoodInfo($food)
    {
        $all_food_units = Unit::getFoodUnitsWithCalories($food);
        $food_units = $food->units()->lists('unit_id');

        return [
            "all_food_units" => $all_food_units,
            "food" => $food,
            "food_units" => $food_units
        ];
    }

    /**
     * Get all foods, along with their default unit, default calories,
     * and all their units.
     * Also, add the calories for each food's units. Todo?
     * @return mixed
     */
    public function getFoods()
    {
        $foods = Food::forCurrentUser()
            ->with('defaultUnit')
//            ->with('units')
            ->orderBy('foods.name', 'asc')->get();

        $foods = transform(createCollection($foods, new FoodTransformer));
        return $foods['data'];
    }

}