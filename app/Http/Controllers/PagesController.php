<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Transformers\JournalTransformer;
use App\Models\Journal\Journal;
use App\Models\Tags\Tag;
use App\Repositories\ExerciseEntriesRepository;
use App\Repositories\ExercisesRepository;
use App\Repositories\FoodsRepository;
use App\Repositories\MenuEntriesRepository;
use App\Repositories\RecipesRepository;
use App\Repositories\UnitsRepository;
use App\Repositories\WeightsRepository;
use App\Repositories\WorkoutsRepository;
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
     * @var ExerciseEntriesRepository
     */
    private $exerciseEntriesRepository;
    /**
     * @var MenuEntriesRepository
     */
    private $menuEntriesRepository;
    /**
     * @var RecipesRepository
     */
    private $recipesRepository;
    /**
     * @var WorkoutsRepository
     */
    private $workoutsRepository;

    /**
     * Create a new controller instance.
     *
     * @param ExercisesRepository $exercisesRepository
     * @param FoodsRepository $foodsRepository
     * @param UnitsRepository $unitsRepository
     * @param ExerciseEntriesRepository $exerciseEntriesRepository
     * @param MenuEntriesRepository $menuEntriesRepository
     * @param RecipesRepository $recipesRepository
     * @param WorkoutsRepository $workoutsRepository
     */
    public function __construct(ExercisesRepository $exercisesRepository, FoodsRepository $foodsRepository, UnitsRepository $unitsRepository, ExerciseEntriesRepository $exerciseEntriesRepository, MenuEntriesRepository $menuEntriesRepository, RecipesRepository $recipesRepository, WorkoutsRepository $workoutsRepository)
    {
        $this->middleware('auth');
        $this->exercisesRepository = $exercisesRepository;
        $this->foodsRepository = $foodsRepository;
        $this->unitsRepository = $unitsRepository;
        $this->exerciseEntriesRepository = $exerciseEntriesRepository;
        $this->menuEntriesRepository = $menuEntriesRepository;
        $this->recipesRepository = $recipesRepository;
        $this->workoutsRepository = $workoutsRepository;
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
            "exerciseEntries" => $this->exerciseEntriesRepository->getEntriesForTheDay($date),
            "foodUnits" => $this->unitsRepository->getFoodUnits(),
            "exerciseUnits" => $this->unitsRepository->getExerciseUnits(),
            "menu_entries" => $this->menuEntriesRepository->getEntriesForTheDay($date),
            "calories_for_the_day" => $this->menuEntriesRepository->getCaloriesForDay($date),
            "calories_for_the_week" => $this->menuEntriesRepository->getCaloriesFor7Days($date)
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
            'workouts' => $this->workoutsRepository->getWorkouts(),
            'units' => $this->unitsRepository->getExerciseUnits(),
            'exercise_tags' => Tag::forCurrentUser()
                ->where('for', 'exercise')
                ->orderBy('name', 'asc')
                ->get()

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
            'workouts' => $this->workoutsRepository->getWorkouts()
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
            'workouts' => $this->workoutsRepository->getWorkouts(),
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
            'foods' => $this->foodsRepository->getFoods(),
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
            'foods_with_units' => $this->foodsRepository->getFoods(),
            'recipes' => $this->recipesRepository->filterRecipes('', []),
            'recipe_tags' => Tag::forCurrentUser()
                ->where('for', 'recipe')
                ->orderBy('name', 'asc')
                ->get()
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

        $entry = Journal::forCurrentUser()->where('date', $date)->first();
        if ($entry) {
            $entry = transform(createItem($entry, new JournalTransformer))['data'];
        }
        else {
            $entry = [];
        }

        JavaScript::put([
            'entry' => $entry
        ]);

        return view('pages.journal.journal');
    }
}