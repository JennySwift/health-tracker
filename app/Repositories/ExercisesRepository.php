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
     *
     * @return mixed
     */
    public function getExercises()
    {
        return Exercise::getExercises();
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