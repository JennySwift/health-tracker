<?php namespace App\Http\Controllers\API\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\WorkoutTransformer;
use App\Models\Exercises\Workout;
use Auth;
use Illuminate\Http\Request;

/**
 * Class WorkoutsController
 * @package App\Http\Controllers\Exercises
 */
class WorkoutsController extends Controller
{

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $workout = new Workout([
            'name' => $request->get('name')
        ]);

        $workout->user()->associate(Auth::user());
        $workout->save();

        return $this->responseCreatedWithTransformer($workout, new WorkoutTransformer);
    }

    /**
     * Deletes all workouts from the series
     * then adds the correct workouts to the series
     *
     * @TODO Should be part of the update method on the SeriesController
     * Todo: refactor into two separate methods
     * PUT /series/{id}
     * @param Request $request
     * @return mixed
     */
    public function deleteAndInsertSeriesIntoWorkouts(Request $request)
    {
        // Fetch the series
        $series = Series::find($request->get('series_id'));

        // If you want to delete the current workouts no matter what,
        // pass an empty array as default value
        $workout_ids = $request->get('workout_ids', []);

        // Synchronize workouts
        $series->workouts()->sync($workout_ids);

        // return Series::getExerciseSeries();
        return Workout::getWorkouts();
    }
}