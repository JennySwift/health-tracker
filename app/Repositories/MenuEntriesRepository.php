<?php

namespace App\Repositories;

use App\Http\Transformers\MenuEntryTransformer;
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
     * Get a user's menu (food/recipe) entries for one day
     * @param $date
     * @return array
     */
    public function getEntriesForTheDay($date)
    {
        $entries = Entry::forCurrentUser()
            ->where('date', $date)
            ->get();

        //            $calories_for_item = Food::getCalories($food_id, $unit_id);
//            $calories_for_quantity = Food::getCaloriesForQuantity($calories_for_item, $quantity);

        return transform(createCollection($entries, new MenuEntryTransformer))['data'];
    }

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
}