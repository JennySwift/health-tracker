<?php

namespace App\Repositories;

use App\Models\Exercises\Series;
use Carbon\Carbon;

/**
 * Class ExerciseSeriesRepository
 * @package App\Repositories
 */
class ExerciseSeriesRepository
{
    /**
     * @var ExerciseEntriesRepository
     */
    private $exerciseEntriesRepository;

    /**
     * @param ExerciseEntriesRepository $exerciseEntriesRepository
     */
    public function __construct(ExerciseEntriesRepository $exerciseEntriesRepository)
    {
        $this->exerciseEntriesRepository = $exerciseEntriesRepository;
    }

    /**
     * Get all the exercise series that belong to the user
     * @return mixed
     */
    public function getExerciseSeries()
    {
        $series = Series::forCurrentUser('exercise_series')->orderBy('name', 'asc')->get();
        return $series;
    }

    /**
     * Get all exercise entries that belong to a series.
     * Calculate the number of days ago,
     * the number of reps,
     * and the number of sets.
     * If entries share the same exercise, date, and unit, compact them into one item.
     * @param $series
     * @return array
     */
    public function getExerciseSeriesHistory($series)
    {
        //get all entries in the series
        $entries = $series->entries()
            ->select(
                'date',
                'exercises.id as exercise_id',
                'exercises.name',
                'exercises.description',
                'exercises.step_number',
                'quantity',
                'exercise_unit_id')
            ->with(['unit' => function($query) {
                $query->select('name', 'id');
            }])
            ->orderBy('date', 'desc')->get();

        //Populate an array to return
        return $this->compactExerciseEntriesForHistory($entries);
    }

    /**
     * For getExerciseSeriesHistory.
     * If entries share the same exercise, date, and unit, compact them into one item.
     * @param $entries
     * @return array
     */
    public function compactExerciseEntriesForHistory($entries)
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
                    if ($item['date'] === $date && $item['name'] === $entry->name && $item['unit_name'] === $entry->unit->name) {
                        //the exercise with unit already exists in the array
                        //so we don't want to add it again
                        $counter++;
                    }
                }
            }
            if ($counter === 0) {
                $array[] = array(
                    'date' => $date,
                    'days_ago' => getHowManyDaysAgo($sql_date),
                    'id' => $entry->exercise_id,
                    'name' => $entry->name,
                    'description' => $entry->description,
                    'step_number' => $entry->step_number,
                    'unit_name' => $entry->unit->name,
                    'sets' => $this->exerciseEntriesRepository->getExerciseSets($sql_date, $entry->exercise_id, $entry->exercise_unit_id),
                    'total' => $this->exerciseEntriesRepository->getTotalExerciseReps($sql_date, $entry->exercise_id, $entry->exercise_unit_id),
                    'quantity' => $entry->quantity,
                );
            }
        }

        return $array;
    }
}