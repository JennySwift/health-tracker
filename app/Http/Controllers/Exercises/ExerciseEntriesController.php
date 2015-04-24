<?php namespace App\Http\Controllers\Exercises;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ExerciseEntriesController extends Controller {

	/**
	 * select
	 */
	
	public function getSpecificExerciseEntries (Request $request) {
		$date = $request->get('date');
		$exercise_id = $request->get('exercise_id');
		$exercise_unit_id = $request->get('exercise_unit_id');	

		//for when user clicks on the exercise entries table, to show the entries for just that exercise
		$entries = DB::table('exercise_entries')
			->where('date', $date)
			->where('exercise_id', $exercise_id)
			->where('exercise_unit_id', $exercise_unit_id)
			->join('exercises', 'exercise_entries.exercise_id', '=', 'exercises.id')
			->join('exercise_units', 'exercise_entries.exercise_unit_id', '=', 'exercise_units.id')
			->select('exercise_id', 'quantity', 'exercises.name', 'exercise_units.name AS unit_name', 'exercise_entries.id AS entry_id')
			->orderBy('exercises.name', 'asc')
			->get();

		return $entries;
	}

	/**
	 * insert
	 */
	
	public function insertExerciseSet (Request $request) {
		include(app_path() . '/inc/functions.php');
		$date = $request->get('date');
		$exercise_id = $request->get('exercise_id');

		$quantity = getDefaultExerciseQuantity($exercise_id);
		$unit_id = getDefaultExerciseUnitId($exercise_id);

		DB::table('exercise_entries')->insert([
			'date' => $date,
			'exercise_id' => $exercise_id,
			'quantity' => $quantity,
			'exercise_unit_id' => $unit_id,
			'user_id' => Auth::user()->id
		]);
		
		return getExerciseEntries($date);
	}

	public function insertExerciseEntry (Request $request) {
		include(app_path() . '/inc/functions.php');
		$data = $request->get('data');
		$date = $data['date'];		
		$date = $data['date'];
		$new_entry = $data['new_entry'];

		DB::table('exercise_entries')->insert([
			'date' => $date,
			'exercise_id' => $new_entry['id'],
			'quantity' => $new_entry['quantity'],
			'exercise_unit_id' => $new_entry['unit_id'],
			'user_id' => Auth::user()->id
		]);

		return getExerciseEntries($date);
	}
	
	/**
	 * update
	 */
	
	/**
	 * delete
	 */

	public function deleteExerciseEntry (Request $request) {
		include(app_path() . '/inc/functions.php');
		$id = $request->get('id');
		$date = $request->get('date');

		DB::table('exercise_entries')->where('id', $id)->delete();

		return getExerciseEntries($date);
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
