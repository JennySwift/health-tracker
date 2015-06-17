<?php namespace App\Http\Controllers\Exercises;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Exercises\Workout;
use Illuminate\Http\Request;
use Auth;

use App\Models\Exercises\Series;

/**
 * Class ExerciseSeriesController
 * @package App\Http\Controllers\Exercises
 */
class ExerciseSeriesController extends Controller {

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
     * @return mixed
     */
    public function store(Request $request)
	{
		$name = $request->get('name');
		
		Series
			::insert([
				'name' => $name,
				'user_id' => Auth::user()->id
			]);

		return Series::getExerciseSeries();
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
     * @param $id
     * @return mixed
     */
    public function destroy($id)
	{
        //todo: notify user the series will not be deleted unless it has not been used, due to foreign key constraint
		Series::where('id', $id)->delete();
		return Series::getExerciseSeries();
	}
}
