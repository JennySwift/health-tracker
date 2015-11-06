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
                'exercise_unit_id'
            )
            ->with(['unit' => function($query) {
                $query->select('name', 'id');
            }])
            ->orderBy('date', 'desc')
            ->orderBy('exercise_id', 'desc')
            ->get();

        //Populate an array to return
        return $this->exerciseEntriesRepository->compactExerciseEntries($entries);
    }
}