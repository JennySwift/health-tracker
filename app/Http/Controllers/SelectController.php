<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Debugbar;

use App\Models\Exercises\Exercise;
use App\Models\Exercises\Series as ExerciseSeries;
use App\Models\Exercises\Entry as ExerciseEntry;
use App\Models\Tags\Tag;
use App\Models\Units\Unit;
use App\Models\Exercises\Workout;

use App\Models\Foods\Food;
use App\Models\Foods\FoodEntry;
use App\Models\Foods\Calories;
use App\Models\Foods\Recipe;

use App\Models\Weights\Weight;

use App\Models\Journal\Journal;

class SelectController extends Controller {
	//
	public function pageLoad (Request $request) {
		include(app_path() . '/inc/functions.php');
		$date = $request->get('date');
		$response = array(
			"foods" => Food::getFoods(),
			// "recipes" => getRecipes(),
			"recipes" => Recipe::filterRecipes('', []),
			"food_units" => Unit::getFoodUnits(),
			"foods_with_units" => Food::getAllFoodsWithUnits(),
			"weight" => Weight::getWeight($date),
			"exercise_units" => Unit::getExerciseUnits(),
			"exercises" => Exercise::getExercises(),
			"exercise_series" => ExerciseSeries::getExerciseSeries(),
			"food_entries" => FoodEntry::getFoodEntries($date),
			"calories_for_the_day" => number_format(Calories::getCaloriesForTimePeriod($date, "day"), 2),
			"calories_for_the_week" => number_format(Calories::getCaloriesForTimePeriod($date, "week"), 2),
			"exercise_entries" => ExerciseEntry::getExerciseEntries($date),
			"journal_entry" => Journal::getJournalEntry($date),
			"exercise_tags" => Tag::getExerciseTags(),
			"workouts" => Workout::getWorkouts(),
			"recipe_tags" => Tag::getRecipeTags()
		);
		return $response;
	}

	//Which controller?
	public function getEntries () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$response = array(
			"weight" => Weight::getWeight($date),
			"exercise_entries" => ExerciseEntry::getExerciseEntries($date),
			"journal_entry" => Journal::getJournalEntry($date),

			"food_entries" => FoodEntry::getFoodEntries($date),
			"calories_for_the_day" => number_format(Calories::getCaloriesForTimePeriod($date, "day"), 2),
			"calories_for_the_week" => number_format(Calories::getCaloriesForTimePeriod($date, "week"), 2)
		);
		return $response;
	}
}
