<?php namespace App\Http\Controllers\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Exercises\Entry;
use App\Models\Exercises\Exercise;
use App\Models\Units\Unit;
use Auth;
use Debugbar;
use Illuminate\Http\Request;


/**
 * Class ExerciseEntriesController
 * @package App\Http\Controllers\Exercises
 */
class ExerciseEntriesController extends Controller
{

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
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function insertExerciseSet(Request $request)
    {
        $exercise = Exercise::find($request->get('exercise_id'));

        $entry = new Entry([
            'date' => $request->get('date'),
            'quantity' => Exercise::getDefaultExerciseQuantity($exercise),
        ]);

        $entry->user()->associate(Auth::user());
        $entry->exercise()->associate(Exercise::find($request->get('exercise_id')));
        $entry->unit()->associate(Unit::find(Exercise::getDefaultExerciseUnitId($exercise)));

        $entry->save();

        return $this->responseCreated($entry);
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $entry = new Entry([
            'date' => $request->get('date'),
            'quantity' => $request->get('quantity'),
        ]);

        $entry->user()->associate(Auth::user());
        $entry->exercise()->associate(Exercise::find($request->get('exercise_id')));
        $entry->unit()->associate(Unit::find($request->get('unit_id')));

        $entry->save();

        return $this->responseCreated($entry);
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