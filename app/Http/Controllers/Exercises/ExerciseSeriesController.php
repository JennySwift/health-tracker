<?php namespace App\Http\Controllers\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Exercises\Series;
use App\Models\Exercises\Workout;
use Auth;
use Illuminate\Http\Request;

/**
 * Class ExerciseSeriesController
 * @package App\Http\Controllers\Exercises
 */
class ExerciseSeriesController extends Controller
{

    /**
     * For the exercise series popup
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function show($id)
    {
        $series = Series::find($id);
        $all_workouts = Workout::getWorkouts();
        $workouts = $series->workouts()->lists('workout_id');

        return [
            "all_workouts" => $all_workouts,
            "series" => $series,
            "workouts" => $workouts
        ];
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $series = new Series([
            'name' => $request->get('name')
        ]);
        $series->user()->associate(Auth::user());
        $series->save();

        return $this->responseCreated($series);
    }

    /**
     * Deletes all workouts from the series
     * then adds the correct workouts to the series
     * @param  Request $request [description]
     * @return [type]           [description]
     *
     * @TODO Should be part of the update method on the SeriesController
     * PUT /series/{id}
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

    /**
     *
     * @param Series $series
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Series $series)
    {
        //todo: notify user the series will not be deleted unless it has not been used, due to foreign key constraint
        $series->delete();

        return $this->responseNoContent();
    }
}
