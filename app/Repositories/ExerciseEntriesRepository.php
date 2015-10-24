<?php

namespace App\Repositories;

use App\Models\Exercises\Entry;

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

//        return $entries;

        return $this->compactExerciseEntries($entries, $date);
    }

    /**
     * If entries share the same exercise and unit, compact them into one item.
     * Include the default unit id so I can show the 'add set' button only if the entry uses the default unit.
     *
     * @VP:
     * This method is much the same as Exercise::compactExerciseEntries, but slightly different.
     * Should I combine them into one method or keep them separate?
     * @param $entries
     * @param $date
     * @return array
     */
    public function compactExerciseEntries($entries, $date)
    {
        //create an array to return
        $array = [];

        //populate the array
        foreach ($entries as $entry) {
            $counter = 0;

            //check to see if the array already has the exercise entry
            //so it doesn't appear as a new entry for each set of exercises
            if (count($array) > 0) {
                foreach ($array as $item) {
                    if ($item['name'] === $entry->exercise->name && $item['unit_name'] === $entry->unit->name) {
                        //the exercise with unit already exists in the array
                        //so we don't want to add it again
                        $counter++;
                    }
                }
            }
            if ($counter === 0) {
                $array[] = array(
                    'exercise_id' => $entry->exercise_id,
                    'name' => $entry->exercise->name,
                    'description' => $entry->exercise->description,
                    'step_number' => $entry->exercise->step_number,
                    'unit_id' => $entry->unit->id,
                    'unit_name' => $entry->unit->name,
                    'default_unit_id' => $entry->exercise->default_unit_id,
                    'sets' => Entry::getExerciseSets($date, $entry->exercise_id, $entry->exercise_unit_id),
                    'total' => Entry::getTotalExerciseReps($date, $entry->exercise_id, $entry->exercise_unit_id),
                    'quantity' => $entry->quantity,
                );
            }
        }

        return $array;
    }
}