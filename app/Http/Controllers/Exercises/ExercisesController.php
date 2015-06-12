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
use App\Models\Exercises\Workout;
use App\Models\Units\Unit;
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
			 * The same thing happened when I tried to use 'tags' instead of 'exercise_tags.'
			 */
			'all_exercises' => $this->getExercises(),
			'series' => $this->getExerciseSeries(),
			'workouts' => Workout::getWorkouts(),
			'exercise_tags' => Tag::where('user_id', Auth::user()->id)->where('for', 'exercise')->orderBy('name', 'asc')->get(),
			'units' => Unit::getExerciseUnits()
		]);

		return view('exercises');
	}

	/**
	 * select
	 */
	
	/**
	 * For the exercise popup
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function getExerciseInfo(Request $request)
	{
		$exercise = Exercise::find($request->get('exercise_id'));
		$all_exercise_tags = Tag::getExerciseTags();
		$exercise_tags = $exercise->tags()->lists('id');

		return [
			"all_exercise_tags" => $all_exercise_tags,
			"exercise" => $exercise,
			"tags" => $exercise_tags
		];
	}
	
	/**
	 * For the exercise series popup
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function getExerciseSeriesInfo(Request $request)
	{
		$series = Series::find($request->get('series_id'));
		$all_workouts = Workout::getWorkouts();
		$workouts = $series->workouts()->lists('workout_id');

		return [
			"all_workouts" => $all_workouts,
			"series" => $series,
			"workouts" => $workouts
		];
	}

	public function getExercises()
	{
		return Exercise::getExercises();
	}

	public function getExerciseSeries()
	{
		return Series::getExerciseSeries();
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

	/**
	 * Deletes all workouts from the series then adds the correct workouts to the series
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function deleteAndInsertSeriesIntoWorkouts(Request $request)
	{
		$series = Series::find($request->get('series_id'));
		$workout_ids = $request->get('workout_ids');
		
		//delete workouts from the series
		//I wasn't sure if detach would work for this since I want to delete all tags that belong to the exercise.
		DB::table('series_workout')->where('series_id', $series->id)->delete();

		//add tags to the exercise
		foreach ($workout_ids as $workout_id) {
			//add tag to the exercise
			$series->workouts()->attach($workout_id, ['user_id' => Auth::user()->id]);
		}

		// return Series::getExerciseSeries();
		return Workout::getWorkouts();
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
		// dd($exercise);
				
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
				'default_unit_id' => $default_exercise_unit_id
			]);

		return Exercise::getExercises();
	}

	/**
	 * delete
	 */
	
	public function destroy($id)
	{
		Exercise::where('id', $id)->delete();
		return Exercise::getExercises();
	}
}
