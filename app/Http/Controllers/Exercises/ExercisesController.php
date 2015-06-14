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

/**
 * Class ExercisesController
 * @package App\Http\Controllers\Exercises
 */
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

	/**
	 * select
	 */

    /**
     * For the exercise popup
     * @param $id
     * @return array
     */
	public function show($id)
	{
		$exercise = Exercise::find($id);
		$all_exercise_tags = Tag::getExerciseTags();
		$exercise_tags = $exercise->tags()->lists('id');

		return [
			"all_exercise_tags" => $all_exercise_tags,
			"exercise" => $exercise,
			"tags" => $exercise_tags
		];
	}

    /**
     *
     * @param Request $request
     * @return array
     */
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
     *
     * @TODO Should be part of the update method on the SeriesController
     * PUT /series/{id}
     */
    public function deleteAndInsertSeriesIntoWorkouts(Request $request)
    {
        // Fetch the series
        $series = Series::find($request->get('series_id'));

        // If you want to delete the current workouts no matter what, pass an empty array as default value
        $workout_ids = $request->get('workout_ids', []);

        // Synchronize workouts
        $series->workouts()->sync($workout_ids);

        // return Series::getExerciseSeries();
        return Workout::getWorkouts();
    }

	/**
	 * Deletes all workouts from the series then adds the correct workouts to the series
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
//	public function deleteAndInsertSeriesIntoWorkouts(Request $request)
//	{
//		$series = Series::find($request->get('series_id'));
//		$workout_ids = $request->get('workout_ids');
//
//		//delete workouts from the series
//		//I wasn't sure if detach would work for this since I want to delete all tags that belong to the exercise.
//		DB::table('series_workout')->where('series_id', $series->id)->delete();
//
//		//add tags to the exercise
//		foreach ($workout_ids as $workout_id) {
//			//add tag to the exercise
//			$series->workouts()->attach($workout_id, ['user_id' => Auth::user()->id]);
//		}
//
//		// return Series::getExerciseSeries();
//		return Workout::getWorkouts();
//	}

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function insertTagInExercise(Request $request)
	{
		$exercise_id = $request->get('exercise_id');
		$tag_id = $request->get('tag_id');
		Tag::insertExerciseTag($exercise_id, $tag_id);
		return Exercise::getExercises();
	}

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
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

    /**
     * Update exercise step number
     * @param Request $request
     * @param $exercise
     * @return mixed
     */
	public function update(Request $request, $exercise)
    {
        // Create an array with the new fields merged
        $data = array_merge($exercise->toArray(), $request->only([
            'step_number',
            'default_quantity',
            'description',
            'name'
        ]));

        // Update the model with this array
        $exercise->update($data);

        // Return response
        return response([], 200);

        //$exercise = Exercise::find($id)
            //->update($request->only(['step_number', 'default_quantity']));

        //Valentin says this method should return $exercise
        //return Exercise::getExercises();
    }

//	public function updateExerciseStepNumber(Request $request)
//	{
//		$exercise_id = $request->get('exercise_id');
//		$step_number = $request->get('step_number');
//
//		Exercise
//			::where('id', $exercise_id)
//			->update([
//				'step_number' => $step_number
//			]);
//
//		return Exercise::getExercises();
//	}

    /**
     * Change which series an exercise is in
     * @param Request $request
     * @return mixed
     * @TODO Does not belong to the ExercisesController, but an ExercisesSeriesController
     */
	public function updateExerciseSeries(Request $request)
	{
		$exercise_id = $request->get('exercise_id');
		$series_id = $request->get('series_id');

        $this->generateResponse();

		//for assigning a series to an exercise
		Exercise
			::where('id', $exercise_id)
			->update([
				'series_id' => $series_id
            ]);

		return Exercise::getExercises();
	}

    /**
     *
     * @param Request $request
     * @return mixed
     */
//    public function updateDefaultExerciseQuantity(Request $request)
//	{
//		$id = $request->get('id');
//		$quantity = $request->get('quantity');
//
//		Exercise
//			::where('id', $id)
//			->update([
//				'default_quantity' => $quantity
//			]);
//
//		return Exercise::getExercises();
//	}

    /**
     *
     * @param Request $request
     * @return mixed
     */
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
     * Delete an exercise
     * @param $id
     * @return mixed
     */
    public function destroy($id)
	{
		Exercise::where('id', $id)->delete();
		return Exercise::getExercises();
	}

}

