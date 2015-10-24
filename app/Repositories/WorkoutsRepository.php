<?php namespace App\Repositories;

use App\Models\Exercises\Workout;
use Auth;

/**
 * Class WorkoutsRepository
 * @package App\Repositories
 */
class WorkoutsRepository
{

    /**
     * Get all the user's workouts with the contents of each workout
     * @return mixed
     */
    public function getWorkouts () {
        $workouts = Workout::forCurrentUser()->get();

        //get all the series that are in each workout
        foreach ($workouts as $workout) {
            $workout->contents = $workout->series()->select('exercise_series.id', 'name')->orderBy('name', 'asc')->get();
        }

        return $workouts;
    }

}