<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Debugbar;

use Auth;
use App\User;
use App\Models\Exercises\Exercise;
use App\Models\Exercises\Series as ExerciseSeries;
use App\Models\Exercises\Entry as ExerciseEntry;
use App\Models\Tags\Tag;
use App\Models\Units\Unit;
use App\Models\Exercises\Workout;

use App\Models\Foods\Food;
use App\Models\Foods\FoodEntry;
use App\Models\Foods\Recipe;

use App\Models\Weights\Weight;

use App\Models\Journal\Journal;

class SelectController extends Controller {
	//
	public function pageLoad (Request $request) {
		include(app_path() . '/inc/functions.php');
		$date = $request->get('date');

		$user = User::find(Auth::user()->id);
		$exercise_units = $user->exerciseUnits()->orderBy('name', 'asc')->get();
		$food_units = $user->foodUnits()->orderBy('name', 'asc')->get();
		$recipe_tags = $user->recipeTags()->orderBy('name', 'asc')->get();
		$exercise_tags = $user->exerciseTags()->orderBy('name', 'asc')->get();

		$response = array(
			"foods" => Food::getFoods(),
			"recipes" => Recipe::filterRecipes('', []),
			"food_units" => $food_units,
			"foods_with_units" => User::getAllFoodsWithUnits(),
			"weight" => Weight::getWeight($date),
			"exercise_units" => $exercise_units,
			"exercises" => User::getExercises(),
			"exercise_series" => User::getExerciseSeries(),
			"food_entries" => FoodEntry::getFoodEntries($date),
			"calories_for_the_day" => number_format(Food::getCaloriesForTimePeriod($date, "day"), 2),
			"calories_for_the_week" => number_format(Food::getCaloriesForTimePeriod($date, "week"), 2),
			"exercise_entries" => User::getExerciseEntries($date),
			"journal_entry" => Journal::getJournalEntry($date),
			"exercise_tags" => $exercise_tags,
			"workouts" => User::getWorkouts(),
			"recipe_tags" => $recipe_tags
		);
		return $response;
	}

	//Which controller?
	public function getEntries () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$response = array(
			"weight" => Weight::getWeight($date),
			"exercise_entries" => User::getExerciseEntries($date),
			"journal_entry" => Journal::getJournalEntry($date),

			"food_entries" => FoodEntry::getFoodEntries($date),
			"calories_for_the_day" => number_format(Food::getCaloriesForTimePeriod($date, "day"), 2),
			"calories_for_the_week" => number_format(Food::getCaloriesForTimePeriod($date, "week"), 2)
		);
		return $response;
	}
}
