<?php namespace App\Http\Controllers\API\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Exercises\Exercise;
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
     * @param $id
     * @return array
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
     * @return array
     */
    public function getExerciseSeriesHistory(Request $request)
    {
        //Fetch the series (singular-the series that was clicked on)
        $series = Series::find($request->get('series_id'));

        return Exercise::getExerciseSeriesHistory($series);
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
