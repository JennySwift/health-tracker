<?php

namespace App\Repositories;

use App\Models\Menu\Entry;
use Carbon\Carbon;
use Auth;

/**
 * Class CaloriesRepository
 * @package App\Repositories
 */
class CaloriesRepository
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
        $entries = Entry::forCurrentUser()
            ->where('date', $date)
            ->get();

        //Get the calories for the entries
        foreach ($entries as $entry) {
            $calories_for_day += $entry->getCalories();
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