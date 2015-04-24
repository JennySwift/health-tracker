<?php namespace App\Http\Controllers\Exercises;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Exercise;
use DB;
use Auth;
use Debugbar;

class ExercisesController extends Controller {

	/**
	 * select
	 */
	
	public function autocompleteExercise (Request $request) {
		$exercise = '%' . $request->get('exercise') . '%';
	
		$exercises = Exercise
			::where('name', 'LIKE', $exercise)
			->where('user_id', Auth::user()->id)
			->select('id', 'name', 'description', 'default_exercise_unit_id', 'default_quantity')
			->get();

		return $exercises;
	}
	
	public function getExerciseSeriesHistory (Request $request) {
		//I still need functions.php for the convertDate, getTotalExerciseReps, and getExerciseSets functions.
		include(app_path() . '/inc/functions.php');
		$series_id = $request->get('series_id');

		//first get all exercises in the series
		$exercise_ids = Exercise
			::where('series_id', $series_id)
			->lists('id');

		//get all entries in the series
		$entries = DB::table('exercise_entries')
			->whereIn('exercise_id', $exercise_ids)
			->join('exercises', 'exercise_entries.exercise_id', '=', 'exercises.id')
			->join('exercise_units', 'exercise_entries.exercise_unit_id', '=', 'exercise_units.id')
			->select('date', 'exercises.id as exercise_id', 'exercises.name as exercise_name', 'exercises.description', 'exercises.step_number', 'quantity', 'exercise_unit_id', 'exercise_units.name as unit_name')
			->orderBy('date', 'desc')
			->get();

		$array = array();
		foreach ($entries as $entry) {
			$sql_date = $entry->date;
			$date = convertDate($sql_date, 'user');
			$days_ago = getHowManyDaysAgo($sql_date);
			$exercise_id = $entry->exercise_id;
			$exercise_unit_id = $entry->exercise_unit_id;
			$counter = 0;

			$total = getTotalExerciseReps($sql_date, $exercise_id, $exercise_unit_id);

			$sets = getExerciseSets($sql_date, $exercise_id, $exercise_unit_id);

			//check to see if the array already has the exercise entry so it doesn't appear as a new entry for each set of exercises
			foreach ($array as $item) {
				if ($item['date'] === $date && $item['exercise_name'] === $entry->exercise_name && $item['unit_name'] === $entry->unit_name) {
					//the exercise with unit already exists in the array so we don't want to add it again
					$counter++;
				}
			}
			if ($counter === 0) {
				$array[] = array(
					'date' => $date,
					'days_ago' => $days_ago,
					'exercise_id' => $entry->exercise_id,
					'exercise_name' => $entry->exercise_name,
					'description' => $entry->description,
					'step_number' => $entry->step_number,
					'unit_name' => $entry->unit_name,
					'sets' => $sets,
					'total' => $total,
				);
			}	
		}
		
		return $array;
	}

	/**
	 * insert
	 */

	public function deleteAndInsertSeriesIntoWorkouts (Request $request) {
		//deletes all rows with $series_id and then adds all the correct rows for $series_id
		//I need functions.php for insertSeriesIntoWorkout and getExerciseSeries
		include(app_path() . '/inc/functions.php');
		$series_id = $request->get('series_id');
		$workouts = $request->get('workouts');

		//first delete all the rows with $series_id
		DB::table('series_workout')
			->where('series_id', $series_id)
			->delete();
		//then add all the rows for the series_id
		foreach ($workouts as $workout) {
			$workout_id = $workout['id'];
			insertSeriesIntoWorkout($workout_id, $series_id);
		}

		return getExerciseSeries();
	}

	public function insertTagInExercise (Request $request) {
		//come back to this one, because insertExerciseTag is used in functions.php
		include(app_path() . '/inc/functions.php');
		$exercise_id = $request->get('exercise_id');
		$tag_id = $request->get('tag_id');
		insertExerciseTag($exercise_id, $tag_id);
		return getExercises();
	}

	public function insertExercise (Request $request) {
		include(app_path() . '/inc/functions.php');
		$name = $request->get('name');
		$description = $request->get('description');
		
		Exercise::insert([
			'name' => $name,
			'description' => $description,
			'user_id' => Auth::user()->id
		]);

		return getExercises();
	}

	/**
	 * update
	 */
	
	public function updateExerciseStepNumber (Request $request) {
		include(app_path() . '/inc/functions.php');
		$exercise_id = $request->get('exercise_id');
		$step_number = $request->get('step_number');
		
		Exercise
			::where('id', $exercise_id)
			->update([
				'step_number' => $step_number
			]);

		return getExercises();
	}

	public function updateExerciseSeries (Request $request) {
		include(app_path() . '/inc/functions.php');
		$exercise_id = $request->get('exercise_id');
		$series_id = $request->get('series_id');

		//for assigning a series to an exercise
		Exercise
			::where('id', $exercise_id)
			->update([
				'series_id' => $series_id
			]);

		return getExercises();
	}

	public function updateDefaultExerciseQuantity (Request $request) {
		include(app_path() . '/inc/functions.php');
		$id = $request->get('id');
		$quantity = $request->get('quantity');
		
		Exercise
			::where('id', $id)
			->update([
				'default_quantity' => $quantity
			]);

		return getExercises();
	}

	public function updateDefaultExerciseUnit (Request $request) {
		include(app_path() . '/inc/functions.php');
		$exercise_id = $request->get('exercise_id');
		$default_exercise_unit_id = $request->get('default_exercise_unit_id');

		Exercise
			::where('id', $exercise_id)
			->update([
				'default_exercise_unit_id' => $default_exercise_unit_id
			]);

		return getExercises();
	}

	/**
	 * delete
	 */
	
	public function deleteExercise (Request $request) {
		include(app_path() . '/inc/functions.php');
		$id = $request->get('id');

		Exercise::where('id', $id)->delete();
		return getExercises();
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
