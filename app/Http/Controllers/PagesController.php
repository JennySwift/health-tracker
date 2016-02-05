<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Transformers\Exercises\ExerciseEntryTransformer;
use App\Repositories\CaloriesRepository;
use App\Repositories\ExerciseEntriesRepository;
use App\Repositories\MenuEntriesRepository;
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
     * @var CaloriesRepository
     */
    private $caloriesRepository;

    /**
     * Create a new controller instance.
     *
     * @param UnitsRepository $unitsRepository
     * @param ExerciseEntriesRepository $exerciseEntriesRepository
     * @param MenuEntriesRepository $menuEntriesRepository
     * @param CaloriesRepository $caloriesRepository
     */
    public function __construct(
        UnitsRepository $unitsRepository,
        CaloriesRepository $caloriesRepository,
        ExerciseEntriesRepository $exerciseEntriesRepository,
        MenuEntriesRepository $menuEntriesRepository
    ) {
        $this->middleware('auth');
        $this->unitsRepository = $unitsRepository;
        $this->caloriesRepository = $caloriesRepository;
        $this->exerciseEntriesRepository = $exerciseEntriesRepository;
        $this->menuEntriesRepository = $menuEntriesRepository;
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
            "menuEntries" => $this->menuEntriesRepository->getEntriesForTheDay($date),
            "foodUnits" => $this->unitsRepository->getFoodUnits(),
            "caloriesForTheDay" => $this->caloriesRepository->getCaloriesForDay($date),
            "calorieAverageFor7Days" => $this->caloriesRepository->getCaloriesFor7Days($date)
        ]);

        return view('main.home-page');
    }

    /**
     * Jasmine testing page
     * @return \Illuminate\View\View
     */
    public function jasmine()
    {
        return view('SpecRunner');
    }
}