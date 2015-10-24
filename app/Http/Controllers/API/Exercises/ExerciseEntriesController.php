<?php namespace App\Http\Controllers\API\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Exercises\Entry;
use App\Models\Exercises\Exercise;
use App\Models\Units\Unit;
use App\Repositories\ExerciseEntriesRepository;
use Auth;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * Class ExerciseEntriesController
 * @package App\Http\Controllers\Exercises
 */
class ExerciseEntriesController extends Controller
{

    /**
     * @var ExerciseEntriesRepository
     */
    private $exerciseEntriesRepository;

    /**
     * @param ExerciseEntriesRepository $exerciseEntriesRepository
     */
    public function __construct(ExerciseEntriesRepository $exerciseEntriesRepository)
    {
        $this->exerciseEntriesRepository = $exerciseEntriesRepository;
    }

    /**
     * Get the user's exercise entries for the day
     * @param $date
     * @return mixed
     */
    public function index($date)
    {
        return $this->exerciseEntriesRepository->getEntriesForTheDay($date);
    }

    /**
     * Returns all entries for an exercise on a specific date
     * where the exercise has the specified unit
     * @param Request $request
     * @return array
     */
    public function getSpecificExerciseEntries(Request $request)
    {
        $date = $request->get('date');
        $exercise = Exercise::find($request->get('exercise_id'));
        $exercise_unit_id = $request->get('exercise_unit_id');

        return Entry::getSpecificExerciseEntries($date, $exercise, $exercise_unit_id);
    }

    /**
     * Insert an exercise entry.
     * It can be an exercise set.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $exercise = Exercise::find($request->get('exercise_id'));

        if ($request->get('set')) {
            // We are inserting an exercise set
            $quantity = Exercise::getDefaultExerciseQuantity($exercise);
            $unit = Unit::find(Exercise::getDefaultExerciseUnitId($exercise));
        }
        else {
            $quantity = $request->get('quantity');
            $unit = Unit::find($request->get('unit_id'));
        }

        $entry = new Entry([
            'date' => $request->get('date'),
            'quantity' => $quantity,
        ]);

        $entry->user()->associate(Auth::user());
        $entry->exercise()->associate($exercise);
        $entry->unit()->associate($unit);

        $entry->save();

        return response($this->exerciseEntriesRepository->getEntriesForTheDay($request->get('date')), Response::HTTP_CREATED);
    }

    /**
     *
     * @param Entry $entry
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Entry $entry)
    {
        $entry->delete();
        return $this->responseNoContent();
    }
}