<?php namespace App\Http\Controllers\Exercises;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use Debugbar;
use DB;

/**
 * Models
 */
use App\Models\Exercises\Exercise;
use App\Models\Exercises\Series;
use App\Models\Tags\Tag;
use JavaScript;
use App\User;

class ExercisesController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	/**
	 * Index
	 */

	public function index()
	{
		JavaScript::put([
			/**
			 * @VP:
			 * If I instead did:
			 * 'exercises' => $this->getExercises()
			 * it didn't work. Why wouldn't it let me call it 'exercises?'
			 */
			'all_exercises' => $this->getExercises()
		]);

		return view('exercises');
	}

	/**
	 * select
	 */
	
	public function getExercises()
	{
		return Exercise::where('user_id', Auth::user()->id)->get();
	}
	
	public function getExerciseSeriesHistory(Request $request)
	{
		//Fetch the series (singular-the series that was clicked on)
		$series = Series::find($request->get('series_id'));

		return Exercise::getExerciseSeriesHistory($series);
	}

	/**
	 * insert
	 */

	public function deleteAndInsertSeriesIntoWorkouts(Request $request)
	{
		//deletes all rows with $series_id and then adds all the correct rows for $series_id
		$series_id = $request->get('series_id');
		$workouts = $request->get('workouts');
		$series = Series::find($series_id);

		//first delete all the rows with $series_id
		DB::table('series_workout')->where('series_id', $series->id)->delete();

		//then add all the rows for the series_id
		foreach ($workouts as $workout) {
			$workout_id = $workout['id'];
			$series->workouts()->attach($workout_id);
		}

		return Series::getExerciseSeries();
	}

	public function insertTagInExercise(Request $request)
	{
		$exercise_id = $request->get('exercise_id');
		$tag_id = $request->get('tag_id');
		Tag::insertExerciseTag($exercise_id, $tag_id);
		return Exercise::getExercises();
	}

	public function insertExercise(Request $request)
	{
		//Build an Exercise object (without saving in database yet)
		$exercise = new Exercise($request->only('name', 'description'));	
				
		//Attach the current user to the user relationship on the Exercise
		$exercise->user()->associate(Auth::user());
				
		//Save the exercise in the DB
		$exercise->save();

		return Exercise::getExercises();
	}

	/**
	 * update
	 */
	
	public function updateExerciseStepNumber(Request $request)
	{
		$exercise_id = $request->get('exercise_id');
		$step_number = $request->get('step_number');
		
		Exercise
			::where('id', $exercise_id)
			->update([
				'step_number' => $step_number
			]);

		return Exercise::getExercises();
	}

	public function updateExerciseSeries(Request $request)
	{
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

	public function updateDefaultExerciseQuantity(Request $request)
	{
		$id = $request->get('id');
		$quantity = $request->get('quantity');
		
		Exercise
			::where('id', $id)
			->update([
				'default_quantity' => $quantity
			]);

		return Exercise::getExercises();
	}

	public function updateDefaultExerciseUnit(Request $request)
	{
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
	
	public function deleteExercise(Request $request)
	{
		$id = $request->get('id');

		Exercise::where('id', $id)->delete();
		return Exercise::getExercises();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	// public function index()
	// {
	// 	//
	// }

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
