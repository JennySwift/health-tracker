<?php

namespace App\Repositories;

use App\Models\Menu\Entry;
use App\Models\Menu\Food;
use Carbon\Carbon;
use Auth;

/**
 * Class MenuEntriesRepository
 * @package App\Repositories
 */
class MenuEntriesRepository
{

    /**
     *
     * @param $date
     * @return int|mixed
     */
    public function getCaloriesForDay($date)
    {
        $calories_for_day = 0;

        //Get the user's food entries for the date
        $entries = Entry
            ::where('date', $date)
            ->where('food_entries.user_id', Auth::user()->id)
            ->select('food_entries.food_id', 'food_entries.unit_id', 'quantity', 'date')
            ->get();

        //Get the calories for the entries
        foreach ($entries as $entry) {
//            $calories_for_item = Food::getCalories($entry->food_id, $entry->unit_id);
//            $calories_for_quantity = Food::getCaloriesForQuantity($calories_for_item, $entry->quantity);
//            $calories_for_day += $calories_for_quantity;
        }

        return $calories_for_day;
    }

    /**
     * Get total calories for 7 days ago, starting from $date.
     * Return the average/day.
     * @param $date
     * @return float
     */
    public function getCaloriesFor7Days($date)
    {
        $total = 0;

        foreach (range(0, 6) as $days) {
            $calories_for_one_day = $this->getCaloriesForDay(Carbon::createFromFormat('Y-m-d', $date)
                ->subDays($days)
                ->format('Y-m-d'));

            $total+= $calories_for_one_day;
        }

        return $total / 7;
    }


    /**
     * Get a user's food entries for one day
     * @param $date
     * @return array
     */
    public function getEntriesForTheDay($date)
    {
        $rows = Entry
            ::join('foods', 'food_entries.food_id', '=', 'foods.id')
            ->join('units', 'food_entries.unit_id', '=', 'units.id')
            ->leftJoin('recipes', 'food_entries.recipe_id', '=', 'recipes.id')
            ->where('date', $date)
            ->where('food_entries.user_id', Auth::user()->id)
            ->select('food_id', 'foods.name AS food_name', 'food_entries.id AS entry_id', 'units.id AS unit_id', 'units.name AS unit_name', 'quantity', 'recipes.name AS recipe_name', 'recipes.id AS recipe_id')
            ->get();


        $food_entries = array();

        foreach ($rows as $row) {
            $food_id = $row->food_id;
            $food_name = $row->food_name;
            $quantity = $row->quantity;
            $entry_id = $row->entry_id;
            $unit_id = $row->unit_id;
            $unit_name = $row->unit_name;
            $recipe_name = $row->recipe_name;
            $recipe_id = $row->recipe_id;

//            $calories_for_item = Food::getCalories($food_id, $unit_id);
//            $calories_for_quantity = Food::getCaloriesForQuantity($calories_for_item, $quantity);

            $food_entries[] = array(
                "food_id" => $food_id,
                "food_name" => $food_name,
                "quantity" => $quantity,
                "entry_id" => $entry_id,
                "unit_id" => $unit_id,
                "unit_name" => $unit_name,
//                "calories" => $calories_for_quantity,
                "recipe_name" => $recipe_name,
                "recipe_id" => $recipe_id
            );
        }

        return $food_entries;
    }
}