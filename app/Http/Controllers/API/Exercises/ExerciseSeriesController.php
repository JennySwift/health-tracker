<?php namespace App\Http\Controllers\API\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Exercises\Exercise;
use App\Models\Exercises\Series;
use App\Models\Exercises\Workout;
use App\Repositories\ExerciseSeriesRepository;
use Auth;
use Illuminate\Http\Request;

/**
 * Class ExerciseSeriesController
 * @package App\Http\Controllers\Exercises
 */
class ExerciseSeriesController extends Controller
{
    /**
     * @var ExerciseSeriesRepository
     */
    private $exerciseSeriesRepository;

    /**
     * @param ExerciseSeriesRepository $exerciseSeriesRepository
     */
    public function __construct(ExerciseSeriesRepository $exerciseSeriesRepository)
    {
        $this->exerciseSeriesRepository = $exerciseSeriesRepository;
    }

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
