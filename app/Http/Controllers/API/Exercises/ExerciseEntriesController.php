<?php namespace App\Http\Controllers\API\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\Exercises\ExerciseEntryTransformer;
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
        return transform(
            createCollection(
                $this->exerciseEntriesRepository->getEntriesForTheDay($date),
                new ExerciseEntryTransformer
            )
        )['data'];
    }

    /**
     * Returns all entries for an exercise on a specific date
     * where the exercise has the specified unit
     *
     * Get all entries for one exercise with a particular unit on a particular date.
     * Get exercise name, quantity, and entry id.
     * @param Request $request
     * @return array
     */
    public function getSpecificExerciseEntries(Request $request)
    {
        $exercise = Exercise::find($request->get('exercise_id'));
        $entries = Entry::where('exercise_id', $exercise->id)
            ->where('date', $request->get('date'))
            ->where('exercise_unit_id', $request->get('exercise_unit_id'))
            ->with('exercise')
            ->get();

        return $entries;
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

        if ($request->get('exerciseSet')) {
            // We are inserting an exercise set
            $quantity = $exercise->default_quantity;
            $unit = Unit::find($exercise->default_unit_id);
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

        //Return the entries for the day
        $entries = transform(
            createCollection(
                $this->exerciseEntriesRepository->getEntriesForTheDay($request->get('date')),
                new ExerciseEntryTransformer
            )
        )['data'];

        return response($entries, Response::HTTP_CREATED);
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