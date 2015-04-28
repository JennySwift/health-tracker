<?php namespace App\Http\Controllers\Exercises;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Exercises\Exercise;
use App\Models\Exercises\Entry as ExerciseEntry;
use DB;
use Auth;
use Debugbar;

class ExerciseEntriesController extends Controller {

	/**
	 * select
	 */
	
	public function getSpecificExerciseEntries (Request $request) {
		//returns the entries for the specified exercise on a specific date			
		$date = $request->get('date');
		$exercise_id = $request->get('exercise_id');
		//Is the unit id necessary?
		$exercise_unit_id = $request->get('exercise_unit_id');	

		// First method
		// $exercise = Exercise::find($request->get('exercise_id'));
		// $entries = $exercise->with(['entries' => function($query) use ($date)
		// {
		//     $query->where('date', $date);
		// }])->get();
				
		//If you need ordering, just add: ->orderBy('name') before ->get()
				
		// Second method
		// $entries = Entry::whereDate($date)->with(['exercise' => function($query) use ($exercise_id) {
		//     $query->whereId($exercise_id);
		//     $query->orderBy('name');
		// }])->get();
				
		// Collection of entries => exercise

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

		$quantity = Exercise::getDefaultExerciseQuantity($exercise_id);
		$unit_id = Exercise::getDefaultExerciseUnitId($exercise_id);

		DB::table('exercise_entries')->insert([
			'date' => $date,
			'exercise_id' => $exercise_id,
			'quantity' => $quantity,
			'exercise_unit_id' => $unit_id,
			'user_id' => Auth::user()->id
		]);
		
		return ExerciseEntry::getExerciseEntries($date);
	}

	public function insertExerciseEntry (Request $request) {
		$data = $request->all();
		$date = $data['date'];
		$new_entry = $data['new_entry'];

		DB::table('exercise_entries')->insert([
			'date' => $date,
			'exercise_id' => $new_entry['id'],
			'quantity' => $new_entry['quantity'],
			'exercise_unit_id' => $new_entry['unit_id'],
			'user_id' => Auth::user()->id
		]);

		return ExerciseEntry::getExerciseEntries($date);
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
