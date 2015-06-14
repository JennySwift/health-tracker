<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Exercises\Workout;
use App\Models\Tags\Tag;
use App\Models\Units\Unit;
use App\Repositories\Exercises\ExercisesRepository;
use JavaScript;
use Auth;

use Illuminate\Http\Request;

class PagesController extends Controller {

    public function __construct()
{
    $this->middleware('auth');
}

    public function exercises(ExercisesRepository $exercisesRepository)
    {
        JavaScript::put([
            /**
             * @VP:
             * If I instead did:
             * 'exercises' => $this->getExercises()
             * it didn't work. Why wouldn't it let me call it 'exercises?'
             * The same thing happened when I tried to use 'tags' instead of 'exercise_tags.'
             */
            'all_exercises' => $exercisesRepository->getExercises(),
            'series' => $exercisesRepository->getExerciseSeries(),
            'workouts' => Workout::getWorkouts(),
            'exercise_tags' => Tag::where('user_id', Auth::user()->id)->where('for', 'exercise')->orderBy('name', 'asc')->get(),
            'units' => Unit::getExerciseUnits()
        ]);

        return view('exercises');
    }

}
