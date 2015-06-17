<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Weights\WeightsRepository;
use JavaScript;
use Carbon\Carbon;

/**
 * Models
 */
use App\Models\Weights\Weight;
use App\Models\Foods\Entry as FoodEntry;
use App\Models\Exercises\Entry as ExerciseEntry;
use App\Models\Units\Unit;
use App\User;
use App\Models\Journal\Journal;
use App\Models\Foods\Food;

class EntriesController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

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
