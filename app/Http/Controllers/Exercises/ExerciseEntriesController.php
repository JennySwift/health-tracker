<?php namespace App\Http\Controllers\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Exercises\Entry;
use App\Models\Exercises\Exercise;
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
     * @return array
     */
    public function insertExerciseSet(Request $request)
    {
        $date = $request->get('date');
        $exercise_id = $request->get('exercise_id');

        $quantity = Exercise::getDefaultExerciseQuantity($exercise_id);
        $unit_id = Exercise::getDefaultExerciseUnitId($exercise_id);

        Entry::insert([
            'date' => $date,
            'exercise_id' => $exercise_id,
            'quantity' => $quantity,
            'exercise_unit_id' => $unit_id,
            'user_id' => Auth::user()->id
        ]);

        return Entry::getExerciseEntries($date);
    }

    /**
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $date = $data['date'];
        $new_entry = $data['new_entry'];

        Entry::insert([
            'date' => $date,
            'exercise_id' => $new_entry['id'],
            'quantity' => $new_entry['quantity'],
            'exercise_unit_id' => $new_entry['unit_id'],
            'user_id' => Auth::user()->id
        ]);

        return Entry::getExerciseEntries($date);
    }

    /**
     * Delete exercise entry.
     * Return the info to update the popup.
     * @param Request $request
     * @return array
     */
//    public function deleteExerciseEntry(Request $request)
//    {
//        $date = $request->get('date');
//        $exercise = Exercise::forCurrentUser()->findOrFail($request->get('exercise_id'));
//        $exercise_unit_id = $request->get('exercise_unit_id');
//
//        $entry = Entry::forCurrentUser()->findOrFail($request->get('exercise_id'));
//        $entry->delete();
//
//        return [
//            'entries_for_day' => Entry::getExerciseEntries($date),
//            'entries_for_popup' => Entry::getSpecificExerciseEntries($date, $exercise, $exercise_unit_id)
//        ];
//	}
}