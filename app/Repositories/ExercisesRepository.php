<?php

namespace App\Repositories;

use App\Models\Exercises\Exercise;
use App\Models\Exercises\Series;

/**
 * Class ExercisesRepository
 * @package App\Repositories
 */
class ExercisesRepository
{

    /**
     * Get all exercises for the current user,
     * along with their tags, default unit name
     * and the name of the series each exercise belongs to.
     * Order first by series name, then by step number.
     * @return mixed
     */
    public function getExercises()
    {
        $exercises = Exercise::forCurrentUser('exercises')
            ->with('defaultUnit')
//            ->orderBy('series_name')
            ->orderBy('step_number')
            ->with('series')
            ->with('tags')
            ->get();

        return $exercises;
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
}