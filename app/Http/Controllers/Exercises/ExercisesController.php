<?php namespace App\Http\Controllers\Exercises;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Exercises\Exercise;
use App\Models\Exercises\Entry as ExerciseEntry;
use App\Models\Exercises\Series;
use App\Models\Exercises\ExerciseTag;
use App\Models\Exercises\Workouts\Series as WorkoutSeries;
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

	// public function getExerciseSeriesHistory (Request $request) {
			
	// 		// Fetch the series
	// 		$series = Series::find($request->get('series_id'));
			
	// 		// If you need an exception in case the series does not exist: 
	// 		// $series = Series::findOrFail($request->get('series_id'));

	// 		/*
	// 		  First get all exercises in the series
	// 		  $exercise_ids = $series->exercises->lists('id');
	// 	  */

	// 		// Get all entries in the series
	// 		// Don't forget to create the relationship function in the Series model!
	// 		$entries = $series->entries;
			
	// 		// $entries = Illuminate\Eloquent\Collection
	// 		// $entries->first() => the first entry in the list
	// 		// $entries->filter(function($entry) use ($seriesId){
	// 	  //     $entry->series_id = $seriesId;
	// 		// });
	// 		// $series->intersect();
	// 		// $series->difference();

	//     // Group the entries by exercise
	    

	// 		$array = [];
			
	// 		foreach($entries as $entry) {
			  
	// 		    $exercise = $entry->exercise;
			    
	// 		    $entries->
			  
	// 		}
			
	// 		return $array;
	// 	}

	// public function getExerciseSeriesHistory (Request $request) {
			
	// 		// Fetch the series
	// 		$series = Series::find($request->get('series_id'));
			
	// 		// If you need an exception in case the series does not exist: 
	// 		// $series = Series::findOrFail($request->get('series_id'));

	// 		/*
	// 		  First get all exercises in the series
	// 		  $exercise_ids = $series->exercises->lists('id');
	// 	  */

	// 		// Get all entries in the series
	// 		// Don't forget to create the relationship function in the Series model!
	// 		$entries = $series->entries;
			
	// 		// $entries = Illuminate\Eloquent\Collection
	// 		// $entries->first() => the first entry in the list
	// 		// $entries->filter(function($entry) use ($seriesId){
	// 	  //     $entry->series_id = $seriesId;
	// 		// });
	// 		// $series->intersect();
	// 		// $series->difference();

	//     // Group the entries by exercise
	    

	// 		$array = array();
	// 		// foreach ($entries as $entry) {
	// 		// 	$sql_date = $entry->date;
	// 		// 	$date = convertDate($sql_date, 'user');
	// 		// 	$days_ago = getHowManyDaysAgo($sql_date);
	// 		// 	$exercise_id = $entry->exercise_id;
	// 		// 	$exercise_unit_id = $entry->exercise_unit_id;
	// 		// 	$counter = 0;

	// 		// 	$total = Exercise_entries::getTotalExerciseReps($sql_date, $exercise_id, $exercise_unit_id);

	// 		// 	$sets = Exercise_entries::getExerciseSets($sql_date, $exercise_id, $exercise_unit_id);

	// 		// 	//check to see if the array already has the exercise entry so it doesn't appear as a new entry for each set of exercises
	// 		// 	foreach ($array as $item) {
	// 		// 		if ($item['date'] === $date && $item['exercise_name'] === $entry->exercise_name && $item['unit_name'] === $entry->unit_name) {
	// 		// 			//the exercise with unit already exists in the array so we don't want to add it again
	// 		// 			$counter++;
	// 		// 		}
	// 		// 	}
	// 		// 	if ($counter === 0) {
	// 		// 		$array[] = array(
	// 		// 			'date' => $date,
	// 		// 			'days_ago' => $days_ago,
	// 		// 			'exercise_id' => $entry->exercise_id,
	// 		// 			'exercise_name' => $entry->exercise_name,
	// 		// 			'description' => $entry->description,
	// 		// 			'step_number' => $entry->step_number,
	// 		// 			'unit_name' => $entry->unit_name,
	// 		// 			'sets' => $sets,
	// 		// 			'total' => $total,
	// 		// 		);
	// 		// 	}	
	// 		// }
			
	// 		return $array;
	// 	}
	
	public function getExerciseSeriesHistory (Request $request) {
		//I still need functions.php for convertDate
		include(app_path() . '/inc/functions.php');
		$series_id = $request->get('series_id');

		//first get all exercises in the series
		$exercise_ids = Exercise
			::where('series_id', $series_id)
			->lists('id');

		//get all entries in the series
		$entries = ExerciseEntry::getSeriesEntries($exercise_ids);

		$array = array();
		foreach ($entries as $entry) {
			$sql_date = $entry->date;
			$date = convertDate($sql_date, 'user');
			$days_ago = getHowManyDaysAgo($sql_date);
			$exercise_id = $entry->exercise_id;
			$exercise_unit_id = $entry->exercise_unit_id;
			$counter = 0;

			$total = ExerciseEntry::getTotalExerciseReps($sql_date, $exercise_id, $exercise_unit_id);

			$sets = ExerciseEntry::getExerciseSets($sql_date, $exercise_id, $exercise_unit_id);

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
		$series_id = $request->get('series_id');
		$workouts = $request->get('workouts');

		//first delete all the rows with $series_id
		DB::table('series_workout')
			->where('series_id', $series_id)
			->delete();

		//then add all the rows for the series_id
		foreach ($workouts as $workout) {
			$workout_id = $workout['id'];
			Series_workout::insertSeriesIntoWorkout($workout_id, $series_id);
		}

		return Exercise_series::getExerciseSeries();
	}

	public function insertTagInExercise (Request $request) {
		$exercise_id = $request->get('exercise_id');
		$tag_id = $request->get('tag_id');
		Exercise_tag::insertExerciseTag($exercise_id, $tag_id);
		return Exercise::getExercises();
	}

	public function insertExercise (Request $request) {
			//$name = $request->get('name');
			//$description = $request->get('description');
			
			// We build an Exercise object (without saving in database yet)
			$exercise = new Exercise($request->only('name', 'description'));
			
			/*
			  This will fetch the User model
			  $exercise->user;
			  
			  This will fetch the User relationship object (belongsTo)
			  $exercise->user();
			  
			*/
			
			// Attach the current user to the user relationship on the Exercise
			$exercise->user()->associate(Auth::user());
			
			// We save the exercise in the DB
			$exercise->save();
			
			//Exercise::create([
			//	'name' => $name,
			//	'description' => $description,
			//	'user_id' => Auth::user()->id
			//]);
			
			// $exercise->user->email;

			return Exercise::getExercises();
		}

	// public function insertExercise (Request $request) {
	// 	$name = $request->get('name');
	// 	$description = $request->get('description');
		
	// 	Exercise::create([
	// 		'name' => $name,
	// 		'description' => $description,
	// 		'user_id' => Auth::user()->id
	// 	]);

	// 	return Exercise::getExercises();
	// }

	/**
	 * update
	 */
	
	public function updateExerciseStepNumber (Request $request) {
		$exercise_id = $request->get('exercise_id');
		$step_number = $request->get('step_number');
		
		Exercise
			::where('id', $exercise_id)
			->update([
				'step_number' => $step_number
			]);

		return Exercise::getExercises();
	}

	public function updateExerciseSeries (Request $request) {
		$exercise_id = $request->get('exercise_id');
		$series_id = $request->get('series_id');

		//for assigning a series to an exercise
		Exercise
			::where('id', $exercise_id)
			->update([
				'series_id' => $series_id
			]);

		return Exercise::getExercises();
	}

	public function updateDefaultExerciseQuantity (Request $request) {
		$id = $request->get('id');
		$quantity = $request->get('quantity');
		
		Exercise
			::where('id', $id)
			->update([
				'default_quantity' => $quantity
			]);

		return Exercise::getExercises();
	}

	public function updateDefaultExerciseUnit (Request $request) {
		$exercise_id = $request->get('exercise_id');
		$default_exercise_unit_id = $request->get('default_exercise_unit_id');

		Exercise
			::where('id', $exercise_id)
			->update([
				'default_exercise_unit_id' => $default_exercise_unit_id
			]);

		return Exercise::getExercises();
	}

	/**
	 * delete
	 */
	
	public function deleteExercise (Request $request) {
		$id = $request->get('id');

		Exercise::where('id', $id)->delete();
		return Exercise::getExercises();
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
