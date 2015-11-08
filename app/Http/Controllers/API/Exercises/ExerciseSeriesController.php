<?php namespace App\Http\Controllers\API\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\SeriesTransformer;
use App\Models\Exercises\Exercise;
use App\Models\Exercises\Series;
use App\Models\Exercises\Workout;
use App\Repositories\ExerciseSeriesRepository;
use App\Repositories\WorkoutsRepository;
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
     * @var WorkoutsRepository
     */
    private $workoutsRepository;

    /**
     * @param ExerciseSeriesRepository $exerciseSeriesRepository
     * @param WorkoutsRepository $workoutsRepository
     */
    public function __construct(ExerciseSeriesRepository $exerciseSeriesRepository, WorkoutsRepository $workoutsRepository)
    {
        $this->exerciseSeriesRepository = $exerciseSeriesRepository;
        $this->workoutsRepository = $workoutsRepository;
    }

    /**
     *
     * @return mixed
     */
    public function index()
    {
        return transform(createCollection($this->exerciseSeriesRepository->getExerciseSeries(), new SeriesTransformer));
    }

    /**
     * For the exercise series popup
     * @param Series $series
     * @return array
     */
    public function show(Series $series)
    {
        return transform(createItem($series, new SeriesTransformer));
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
     * @param Request $request
     * @param Series $series
     * @return mixed
     */
    public function update(Request $request, Series $series)
    {
        // Create an array with the new fields merged
        $data = array_compare($series->toArray(), $request->only([
            'name'
        ]));
//        dd($data);

        $series->update($data);

        if ($request->has('workout_ids')) {
            $series->workouts()->sync($request->get('workout_ids'));
//            $series->save();
        }

        return $this->responseOkWithTransformer($series, new SeriesTransformer);
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
