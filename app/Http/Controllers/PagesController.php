<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Exercises\Workout;
use App\Models\Exercises\Entry as ExerciseEntry;
use App\Models\Foods\Food;
use App\Models\Foods\Recipe;
use App\Models\Foods\Entry as FoodEntry;
use App\Models\Journal\Journal;
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

        return view('pages.entries.entries');
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

        return view('pages.exercises.exercises');
    }

    /**
     * Exercise series page
     * @param ExercisesRepository $exercisesRepository
     * @return \Illuminate\View\View
     */
    public function series(ExercisesRepository $exercisesRepository)
    {
        JavaScript::put([
            'series' => $exercisesRepository->getExerciseSeries(),
            'workouts' => Workout::getWorkouts()
        ]);

        return view('pages.exercises.series');
    }

    /**
     * Workouts page
     * @param ExercisesRepository $exercisesRepository
     * @return \Illuminate\View\View
     */
    public function workouts(ExercisesRepository $exercisesRepository)
    {
        JavaScript::put([
            'workouts' => Workout::getWorkouts(),
        ]);

        return view('pages.exercises.workouts');
    }

    /**
     * Exercise tags page
     * @param ExercisesRepository $exercisesRepository
     * @return \Illuminate\View\View
     */
    public function exerciseTags(ExercisesRepository $exercisesRepository)
    {
        JavaScript::put([
            'exercise_tags' => Tag::where('user_id', Auth::user()->id)->where('for', 'exercise')->orderBy('name', 'asc')->get(),

        ]);

        return view('pages.exercises.exercise-tags');
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

        return view('pages.foods.foods-page');
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function recipes()
    {
        JavaScript::put([
            'foods_with_units' => Food::getAllFoodsWithUnits(),
            'recipes' => Recipe::filterRecipes('', []),
            'recipe_tags' => Tag::getRecipeTags()
        ]);

        return view('pages.foods.recipes-page');
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function foodUnits()
    {
        JavaScript::put([
            'units' => Unit::getAllUnits()
        ]);

        return view('pages.foods.food-units');
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function exerciseUnits()
    {
        JavaScript::put([
            'units' => Unit::getAllUnits()
        ]);

        return view('pages.exercises.exercise-units');
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

        return view('pages.journal.journal');
    }
}