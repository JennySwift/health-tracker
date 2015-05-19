<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Models
 */
use App\Models\Weights\Weight;
use App\Models\Foods\Entry as FoodEntry;
use App\Models\Exercises\Entry as ExerciseEntry;
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

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('entries');
	}

	public function getEntries(Request $request)
	{
		$date = $request->get('date');
		$response = array(
			"weight" => Weight::getWeight($date),
			"exercise_entries" => ExerciseEntry::getExerciseEntries($date),
			"journal_entry" => Journal::getJournalEntry($date),

			"food_entries" => FoodEntry::getFoodEntries($date),
			"calories_for_the_day" => number_format(Food::getCaloriesForTimePeriod($date, "day"), 2),
			"calories_for_the_week" => number_format(Food::getCaloriesForTimePeriod($date, "week"), 2)
		);
		return $response;
	}
}
