<?php namespace App\Http\Controllers;

use App\Models\Exercises\Entry as ExerciseEntry;
use App\Models\Foods\Entry as FoodEntry;
use App\Models\Foods\Food;
use App\Repositories\Weights\WeightsRepository;
use Illuminate\Http\Request;
use JavaScript;

/**
 * Class EntriesController
 * @package App\Http\Controllers
 */
class EntriesController extends Controller
{

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
     * @param Request $request
     * @param WeightsRepository $weightsRepository
     * @return array
     */
    public function getEntries(Request $request, WeightsRepository $weightsRepository)
    {
        $date = $request->get('date');

        $response = array(
            "weight" => $weightsRepository->getWeight($date),
            "exercise_entries" => ExerciseEntry::getExerciseEntries($date),
            "menu_entries" => FoodEntry::getFoodEntries($date),
            "calories_for_the_day" => number_format(Food::getCaloriesForDay($date), 2),
            "calories_for_the_week" => number_format(Food::getCaloriesFor7Days($date), 2)
        );

        return $response;
	}
}