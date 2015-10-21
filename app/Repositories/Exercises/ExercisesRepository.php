<?php
/**
 * Created by PhpStorm.
 * User: 2013
 * Date: 14/06/15
 * Time: 6:56 PM
 */

namespace App\Repositories\Exercises;


use App\Models\Exercises\Exercise;
use App\Models\Exercises\Series;

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