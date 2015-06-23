<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Exercises\Workout;
use App\Models\Exercises\Entry as ExerciseEntry;
use App\Models\Foods\Food;
use App\Models\Foods\Recipe;
use App\Models\Foods\Entry as FoodEntry;
use App\Models\Journal\Journal;
use App\Models\Projects\Payee;
use App\Models\Projects\Payer;
use App\Models\Tags\Tag;
use App\Models\Units\Unit;
use App\Repositories\Exercises\ExercisesRepository;
use App\Repositories\Weights\WeightsRepository;
use Carbon\Carbon;
use JavaScript;
use Auth;

use Illuminate\Http\Request;

/**
 * Class PagesController
 * @package App\Http\Controllers
 */
class PagesController extends Controller {

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
     *
     * @param WeightsRepository $weightsRepository
     * @return \Illuminate\View\View
     */
    public function entries(WeightsRepository $weightsRepository)
    {
        $date = Carbon::today()->format('Y-m-d');

        JavaScript::put([
            "weight" => $weightsRepository->getWeight($date),
            "exercise_entries" => ExerciseEntry::getExerciseEntries($date),
            "food_units" => Unit::getFoodUnits(),
            "exercise_units" => Unit::getExerciseUnits(),

            "menu_entries" => FoodEntry::getFoodEntries($date),
            "calories_for_the_day" => number_format(Food::getCaloriesForDay($date), 2),
            "calories_for_the_week" => number_format(Food::getCaloriesFor7Days($date), 2)
        ]);

        return view('entries');
    }


    /**
     *
     * @param ExercisesRepository $exercisesRepository
     * @return \Illuminate\View\View
     */
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

    /**
     *
     * @return \Illuminate\View\View
     */
    public function foods()
    {
        JavaScript::put([
            'foods_with_units' => Food::getAllFoodsWithUnits(),
            'recipes' => Recipe::filterRecipes('', []),
            'recipe_tags' => Tag::getRecipeTags()
        ]);

        return view('foods');
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function units()
    {
        JavaScript::put([
            'units' => Unit::getAllUnits()
        ]);

        return view('units');
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function journal()
    {
        $date = Carbon::today()->format('Y-m-d');

        JavaScript::put([
            'entry' => Journal::getJournalEntry($date)
        ]);

        return view('journal');
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function payee()
    {
        $payee = Payee::find(Auth::user()->id);

        JavaScript::put([
            'payee_projects' => $payee->projects->toArray(),
            'payers' => $payee->payers->toArray(),
        ]);

        return view('payee');
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function payer()
    {
        $payer = Payer::find(Auth::user()->id);

        JavaScript::put([
            'payer_projects' => $payer->projects,
            'payees' => $payer->payees,
            'me' => Auth::user()
        ]);

        return view('payer');
    }

}