<?php namespace App\Http\Controllers\Exercises;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use Debugbar;

/**
 * Models
 */
use App\Models\Exercises\Exercise;
use App\Models\Exercises\Entry;

class ExerciseEntriesController extends Controller {

	/**
	 * select
	 */
	
	public function getSpecificExerciseEntries(Request $request)
	{
		//returns all entries for an exercise on a specific date where the exercise has the specified unit			
		$date = $request->get('date');
		$exercise = Exercise::find($request->get('exercise_id'));
		$exercise_unit_id = $request->get('exercise_unit_id');

		return Entry::getSpecificExerciseEntries($date, $exercise, $exercise_unit_id);
	}

	/**
	 * insert
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
		
		return User::getExerciseEntries($date);
	}

	public function insertExerciseEntry(Request $request)
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

		return User::getExerciseEntries($date);
	}
	
	/**
	 * update
	 */
	
	/**
	 * delete
	 */

	public function deleteExerciseEntry(Request $request)
	{
		$id = $request->get('id');
		$date = $request->get('date');

		Entry::where('id', $id)->delete();

		return User::getExerciseEntries($date);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
