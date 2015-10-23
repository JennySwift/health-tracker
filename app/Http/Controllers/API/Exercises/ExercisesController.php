<?php namespace App\Http\Controllers\API\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Exercises\Exercise;
use App\Models\Exercises\Series;
use App\Models\Tags\Tag;
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
        $all_exercise_tags = Tag::getExerciseTags();
        $exercise_tags = $exercise->tags()->lists('id');

        return [
            "all_exercise_tags" => $all_exercise_tags,
            "exercise" => $exercise,
            "tags" => $exercise_tags
        ];
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
     * @param $exercise
     * @return Response
     */
    public function update(Request $request, $exercise)
    {
        // Create an array with the new fields merged
        // @TODO Watch User Mass Settings on Laracasts (warning, some advanced OOP concepts in there!)
        $data = array_compare($exercise->toArray(), $request->get('exercise'));

        // Update the model with this array
        $exercise->update($data);

        // Take care of the relationships!!
        if ($request->has('series_id')) {
            $series = Series::findOrFail($request->get('series_id'));
            $exercise->series()->associate($series);
            $exercise->save();
        }

        return $this->responseOk($exercise);
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