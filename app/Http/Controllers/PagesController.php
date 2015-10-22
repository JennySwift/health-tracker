<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Exercises\Entry as ExerciseEntry;
use App\Models\Exercises\Workout;
use App\Models\Menu\Entry as FoodEntry;
use App\Models\Menu\Food;
use App\Models\Menu\Recipe;
use App\Models\Journal\Journal;
use App\Models\Tags\Tag;
use App\Models\Units\Unit;
use App\Repositories\ExercisesRepository;
use App\Repositories\FoodsRepository;
use App\Repositories\UnitsRepository;
use App\Repositories\WeightsRepository;
use Auth;
use Carbon\Carbon;
use JavaScript;

/**
 * Class PagesController
 * @package App\Http\Controllers
 */
class PagesController extends Controller
{
    /**
     * @var ExercisesRepository
     */
    private $exercisesRepository;
    /**
     * @var FoodsRepository
     */
    private $foodsRepository;
    /**
     * @var UnitsRepository
     */
    private $unitsRepository;

    /**
     * Create a new controller instance.
     *
     * @param ExercisesRepository $exercisesRepository
     * @param FoodsRepository $foodsRepository
     * @param UnitsRepository $unitsRepository
     */
    public function __construct(ExercisesRepository $exercisesRepository, FoodsRepository $foodsRepository, UnitsRepository $unitsRepository)
    {
        $this->middleware('auth');
        $this->exercisesRepository = $exercisesRepository;
        $this->foodsRepository = $foodsRepository;
        $this->unitsRepository = $unitsRepository;
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
            "food_units" => $this->unitsRepository->getFoodUnits(),
            "exercise_units" => $this->unitsRepository->getExerciseUnits(),
            "menu_entries" => FoodEntry::getFoodEntries($date),
            "calories_for_the_day" => Food::getCaloriesForDay($date),
            "calories_for_the_week" => Food::getCaloriesFor7Days($date)
        ]);

        return view('pages.entries.entries');
    }


    /**
     *
     * @param ExercisesRepository $exercisesRepository
     * @return \Illuminate\View\View
     */
    public function exercises()
    {
        JavaScript::put([
            /**
             * @VP:
             * If I instead did:
             * 'exercises' => $this->getExercises()
             * it didn't work. Why wouldn't it let me call it 'exercises?'
             * The same thing happened when I tried to use 'tags' instead of 'exercise_tags.'
             */
            'all_exercises' => $this->exercisesRepository->getExercises(),
            'series' => $this->exercisesRepository->getExerciseSeries(),
            'workouts' => Workout::getWorkouts(),
            'exercise_tags' => Tag::where('user_id', Auth::user()->id)->where('for', 'exercise')->orderBy('name',
                'asc')->get(),
            'units' => $this->unitsRepository->getExerciseUnits()
        ]);

        return view('pages.exercises.exercises');
    }

    /**
     * Exercise series page
     * @return \Illuminate\View\View
     */
    public function series()
    {
        JavaScript::put([
            'series' => $this->exercisesRepository->getExerciseSeries(),
            'workouts' => Workout::getWorkouts()
        ]);

        return view('pages.exercises.series');
    }

    /**
     * Workouts page
     * @return \Illuminate\View\View
     */
    public function workouts()
    {
        JavaScript::put([
            'workouts' => Workout::getWorkouts(),
        ]);

        return view('pages.exercises.workouts');
    }

    /**
     * Exercise tags page
     * @return \Illuminate\View\View
     */
    public function exerciseTags()
    {
        JavaScript::put([
            'exercise_tags' => Tag::forCurrentUser()
                ->where('for', 'exercise')
                ->orderBy('name', 'asc')
                ->get(),

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
            'foods_with_units' => $this->foodsRepository->getAllFoodsWithUnits(),
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
            'foods_with_units' => $this->foodsRepository->getAllFoodsWithUnits(),
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
//        return $this->unitsRepository->getFoodUnits();
        JavaScript::put([
            'units' => $this->unitsRepository->getFoodUnits()
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
            'units' => $this->unitsRepository->getExerciseUnits()
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