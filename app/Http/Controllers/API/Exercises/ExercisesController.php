<?php namespace App\Http\Controllers\API\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\ExerciseTransformer;
use App\Models\Exercises\Exercise;
use App\Models\Exercises\Series;
use App\Models\Tags\Tag;
use App\Models\Units\Unit;
use App\Repositories\ExercisesRepository;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JavaScript;


/**
 * Class ExercisesController
 * @package App\Http\Controllers\Exercises
 */
class ExercisesController extends Controller
{
    /**
     * @var ExercisesRepository
     */
    private $exercisesRepository;

    /**
     * Create a new controller instance.
     *
     * @param ExercisesRepository $exercisesRepository
     */
    public function __construct(ExercisesRepository $exercisesRepository)
    {
        $this->middleware('auth');
        $this->exercisesRepository = $exercisesRepository;
    }

    /**
     *
     * @return mixed
     */
    public function index()
    {
        return $this->exercisesRepository->getExercises();
    }

    /**
     * For the exercise popup
     * @param $exercise
     * @return array
     */
    public function show($exercise)
    {
        return transform(createItem($exercise, new ExerciseTransformer))['data'];
    }

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $exercise = new Exercise($request->only('name', 'description'));
        $exercise->user()->associate(Auth::user());
        $exercise->save();

        return $this->responseCreated($exercise);
    }

    /**
     *
     * @param Request $request
     * @param Exercise $exercise
     * @return mixed
     */
    public function update(Request $request, Exercise $exercise)
    {
        // Create an array with the new fields merged
        $data = array_compare($exercise->toArray(), $request->only([
            'name', 'step_number', 'default_quantity', 'description'
        ]));

        $exercise->update($data);

        if ($request->has('series_id')) {
            $series = Series::findOrFail($request->get('series_id'));
            $exercise->series()->associate($series);
            $exercise->save();
        }

        if ($request->has('default_unit_id')) {
            $unit = Unit::where('for', 'exercise')->findOrFail($request->get('default_unit_id'));
            $exercise->defaultUnit()->associate($unit);
            $exercise->save();
        }

        return $this->responseOkWithTransformer($exercise, new ExerciseTransformer);
    }

    /**
     *
     * @param Exercise $exercise
     * @return mixed
     * @throws \Exception
     */
    public function destroy(Exercise $exercise = null)
    {
//        if(is_null($exercise)) {
//            return response([
//                'error' => 'Exercise not found.',
//                'status' => Response::HTTP_NOT_FOUND // = 404
//            ], Response::HTTP_NOT_FOUND);
//        }

        $exercise->delete();

        return $this->responseNoContent();
	}
}