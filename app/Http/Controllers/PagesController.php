<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Repositories\CaloriesRepository;
use App\Repositories\MenuEntriesRepository;
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
     * @param MenuEntriesRepository $menuEntriesRepository
     * @param CaloriesRepository $caloriesRepository
     */
    public function __construct(
        CaloriesRepository $caloriesRepository,

        MenuEntriesRepository $menuEntriesRepository
    ) {
        $this->middleware('auth');
        $this->caloriesRepository = $caloriesRepository;
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