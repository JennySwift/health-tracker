<?php

namespace App\Repositories;

use App\Models\Exercises\Entry;
use App\Models\Units\Unit;
use Carbon\Carbon;
use Auth;

/**
 * Class ExerciseEntriesRepository
 * @package App\Repositories
 */
class ExerciseEntriesRepository
{

    /**
     *
     * @return mixed
     */
    public function getEntriesForTheDay($date)
    {
        $entries = Entry::forCurrentUser()
            ->where('date', $date)
            ->orderBy('id', 'asc')
            ->get();

        return $this->compactExerciseEntries($entries, $date);
    }

    /**
     * If entries share the same exercise, date, and unit,
     * compact them into one item.
     * Include the default unit id so I can show the 'add set' button only if the entry uses the default unit.
     * @param $entries
     * @return array
     */
    public function compactExerciseEntries($entries, $date = null)
    {
        //create an array to return
        $array = [];

        //populate the array
        foreach ($entries as $entry) {
            $sql_date = $entry->date;
            $date = Carbon::createFromFormat('Y-m-d', $sql_date)->format('d/m/y');
            $counter = 0;

            //check to see if the array already has the exercise entry
            //so it doesn't appear as a new entry for each set of exercises
            if (count($array) > 0) {
                foreach ($array as $item) {
                    if ($date) {
//                        dd($item['date'], $date, $item->exercise_id, $entry->exercise->id, $item->exercise_unit_id, $entry->unit->id);
                        if ($item['date'] === $date && $item->exercise_id === $entry->exercise->id && $item->exercise_unit_id === $entry->unit->id) {
                            //the exercise with unit already exists in the array
                            //so we don't want to add it again
                            $counter++;
                        }
                    }
                    else {
                        if ($item->exercise_id === $entry->exercise->id && $item->exercise_unit_id === $entry->unit->id) {
                            //the exercise with unit already exists in the array
                            //so we don't want to add it again
                            $counter++;
                        }
                    }

                }
            }
            if ($counter === 0) {
                $entry->calculateSets($sql_date);
                $entry->calculateTotal($sql_date);
                $entry->date = $date;
                $entry->days_ago = getHowManyDaysAgo($sql_date);

                $array[] = $entry;
            }
        }

        return $array;
    }
}