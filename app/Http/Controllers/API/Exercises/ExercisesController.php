<?php namespace App\Http\Controllers\API\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\Exercises\ExerciseTransformer;
use App\Models\Exercises\Exercise;
use App\Models\Exercises\ExerciseProgram;
use App\Models\Exercises\Series;
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
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($request->get('typing')) {
            $exercises = Exercise::forCurrentUser()
                ->where('name', 'LIKE', '%' . $request->get('typing') . '%')
                ->get();

            return $this->transform($this->createCollection($exercises, new ExerciseTransformer));
        }

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
        $exercise = new Exercise($request->only(
            'name',
            'description',
            'step_number',
            'default_quantity',
            'target',
            'priority',
            'stretch'
        ));
        $exercise->user()->associate(Auth::user());
        $exercise->program()->associate(ExerciseProgram::find($request->get('program_id')));
        $exercise->series()->associate(Series::find($request->get('series_id')));
        $exercise->defaultUnit()->associate(Unit::find($request->get('default_unit_id')));
        $exercise->save();

        $exercise = $this->transform($this->createItem($exercise, new ExerciseTransformer))['data'];

        return response($exercise, Response::HTTP_CREATED);
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
            'name',
            'step_number',
            'default_quantity',
            'description',
            'target',
            'priority'
        ]));

        $exercise->update($data);

        if ($request->has('stretch')) {
            $exercise->stretch = $request->get('stretch');
            $exercise->save();
        }

        if ($request->has('series_id')) {
            $series = Series::findOrFail($request->get('series_id'));
            $exercise->series()->associate($series);
            $exercise->save();
        }

        if ($request->has('program_id')) {
            $program = ExerciseProgram::findOrFail($request->get('program_id'));
            $exercise->program()->associate($program);
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