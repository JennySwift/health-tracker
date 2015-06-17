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
     * For the exercise popup
     * @param $id
     * @return array
     */
	public function show($exercise)
	{
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
     * Update exercise step number
     * @param Request $request
     * @param $exercise
     * @return mixed
     */
	public function update(Request $request, $exercise)
    {
        // Create an array with the new fields merged
        // @TODO Watch User Mass Settings on Laracasts (warning, some advanced OOP concepts in there!)
        $data = array_compare($exercise->toArray(), $request->get('exercise'));

        // Update the model with this array
        $exercise->update($data);

        // Take care of the relationships!!
        if($request->has('series_id'))
        {
            $series = Series::findOrFail($request->get('series_id'));
            $exercise->series()->associate($series);
            $exercise->save();
        }

        // Return response
        return response([], 200);

        //Valentin says this method should return $exercise
        //return Exercise::getExercises();
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