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
	
	public function autocompleteExercise () {
		include(app_path() . '/inc/functions.php');
		$exercise = json_decode(file_get_contents('php://input'), true)["exercise"];
		// $exercise = Request::input('exercise');
		$exercise = '%' . $exercise . '%';

		$exercises = Exercise
			::where('name', 'LIKE', $exercise)
			->where('user_id', Auth::user()->id)
			->select('id', 'name', 'description', 'default_exercise_unit_id', 'default_quantity')
			->get();
		   
		return $exercises;
	}
	
	public function getExerciseSeriesHistory () {
		include(app_path() . '/inc/functions.php');
		$series_id = json_decode(file_get_contents('php://input'), true)["series_id"];
		return getExerciseSeriesHistory($series_id);
	}

	public function getSpecificExerciseEntries () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		$exercise_unit_id = json_decode(file_get_contents('php://input'), true)["exercise_unit_id"];
		return getSpecificExerciseEntries($date, $exercise_id, $exercise_unit_id);
	}

	/**
	 * insert
	 */

	public function InsertExerciseTag () {
		//creates a new exercise tag
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		insertNewExerciseTag($name);
		return getExerciseTags();
	}

	public function deleteAndInsertSeriesIntoWorkouts () {
		//deletes all rows with $series_id and then adds all the correct rows for $series_id
		include(app_path() . '/inc/functions.php');
		$series_id = json_decode(file_get_contents('php://input'), true)["series_id"];
		$workouts = json_decode(file_get_contents('php://input'), true)["workouts"];
		deleteAndInsertSeriesIntoWorkouts($series_id, $workouts);
		return getExerciseSeries();
	}

	public function insertSeriesIntoWorkout () {
		include(app_path() . '/inc/functions.php');
		$workout_id = json_decode(file_get_contents('php://input'), true)["workout_id"];
		$series_id = json_decode(file_get_contents('php://input'), true)["series_id"];
		insertSeriesIntoWorkout($workout_id, $series_id);
		return getExerciseSeries();
	}

	public function insertExerciseSet () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		insertExerciseSet($date, $exercise_id);
		
		return getExerciseEntries($date);
	}

	public function insertExerciseSeries () {
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		insertExerciseSeries($name);
		return getExerciseSeries();
	}

	public function insertTagInExercise () {
		include(app_path() . '/inc/functions.php');
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		$tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
		insertExerciseTag($exercise_id, $tag_id);
		return getExercises();
	}

	public function insertTagsInExercise () {
		//deletes all tags then adds the correct tags
		include(app_path() . '/inc/functions.php');
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		$tags = json_decode(file_get_contents('php://input'), true)["tags"];
		deleteTagsFromExercise($exercise_id);
		insertTagsInExercise($exercise_id, $tags);
		return getExercises();
	}

	public function insertExerciseEntry () {
		include(app_path() . '/inc/functions.php');
		$data = json_decode(file_get_contents('php://input'), true);
		$date = $data['date'];
		insertExerciseEntry($data);

		return getExerciseEntries($date);
	}

	public function insertExercise () {
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		$description = json_decode(file_get_contents('php://input'), true)["description"];
		insertExercise($name, $description);
		return getExercises();
	}

	public function insertExerciseUnit () {
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		insertExerciseUnit($name);
		return getExerciseUnits();
	}
	
	public function insertExerciseEntries () {
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$sql = "INSERT INTO exercise_entries (date, exercise, quantity) VALUES ('$date', '$id', $quantity);";
	}

	/**
	 * update
	 */
	
	public function updateExerciseStepNumber () {
		include(app_path() . '/inc/functions.php');
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		$step_number = json_decode(file_get_contents('php://input'), true)["step_number"];
		updateExerciseStepNumber($exercise_id, $step_number);
		return getExercises();
	}

	public function updateExerciseSeries () {
		include(app_path() . '/inc/functions.php');
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		$series_id = json_decode(file_get_contents('php://input'), true)["series_id"];

		updateExerciseSeries($exercise_id, $series_id);

		return getExercises();
	}

	public function updateDefaultExerciseQuantity () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		$quantity = json_decode(file_get_contents('php://input'), true)["quantity"];
		updateDefaultExerciseQuantity($id, $quantity);
		return getExercises();
	}

	public function updateDefaultExerciseUnit () {
		include(app_path() . '/inc/functions.php');
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		$default_exercise_unit_id = json_decode(file_get_contents('php://input'), true)["default_exercise_unit_id"];

		updateDefaultExerciseUnit($exercise_id, $default_exercise_unit_id);

		return getExercises();
	}

	/**
	 * delete
	 */
	
	public function deleteExercise () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		DB::table('exercises')->where('id', $id)->delete();
		return getExercises();
	}

	public function deleteExerciseUnit () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		DB::table('exercise_units')->where('id', $id)->delete();
		return getExerciseUnits();
	}

	public function deleteExerciseEntry () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		DB::table('exercise_entries')->where('id', $id)->delete();

		return getExerciseEntries($date);
	}

	
	public function deleteExerciseSeries () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		deleteExerciseSeries($id);
		return getExerciseSeries();
	}

	public function deleteTagFromExercise () {
		include(app_path() . '/inc/functions.php');
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		$tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
		deleteTagFromExercise($exercise_id, $tag_id);
		return getExercises();
	}

	public function deleteExerciseTag () {
		include(app_path() . '/inc/functions.php');
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		deleteExerciseTag($id);
		return getExerciseTags();
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
