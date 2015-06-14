<?php namespace App\Http\Controllers\Exercises;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Exercises\Workout;
use Illuminate\Http\Request;
use Auth;

use App\Models\Exercises\Series;

class ExerciseSeriesController extends Controller {

	/**
	 * select
	 */

    /**
     * For the exercise series popup
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function show($id)
    {
        $series = Series::find($id);
        $all_workouts = Workout::getWorkouts();
        $workouts = $series->workouts()->lists('workout_id');

        return [
            "all_workouts" => $all_workouts,
            "series" => $series,
            "workouts" => $workouts
        ];
    }
	
	/**
	 * insert
	 */
	
	public function store(Request $request)
	{
		$name = $request->get('name');
		
		Series
			::insert([
				'name' => $name,
				'user_id' => Auth::user()->id
			]);

		return Series::getExerciseSeries();
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */
	
	public function destroy($id)
	{
        //todo: notify user the series will not be deleted unless it has not been used, due to foreign key constraint
		Series::where('id', $id)->delete();
		return Series::getExerciseSeries();
	}
}
