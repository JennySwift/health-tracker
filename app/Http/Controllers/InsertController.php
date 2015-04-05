<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Debugbar;

use Illuminate\Http\Request;

class InsertController extends Controller {

	//
	public function exerciseTag () {
		//creates a new exercise tag
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		insertNewExerciseTag($name);
		return getExerciseTags();
	}

	public function deleteAndInsertSeriesIntoWorkouts () {
		include(app_path() . '/inc/functions.php');
		$series_id = json_decode(file_get_contents('php://input'), true)["series_id"];
		$workouts = json_decode(file_get_contents('php://input'), true)["workouts"];
		deleteAndInsertSeriesIntoWorkouts($series_id, $workouts);
		return getExerciseSeries();
	}

	public function seriesIntoWorkout () {
		//creates a new exercise tag
		include(app_path() . '/inc/functions.php');
		$workout_id = json_decode(file_get_contents('php://input'), true)["workout_id"];
		$series_id = json_decode(file_get_contents('php://input'), true)["series_id"];
		insertSeriesIntoWorkout($workout_id, $series_id);
		return getExerciseSeries();
	}

	public function exerciseSet () {
		//creates a new exercise tag
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		insertExerciseSet($date, $exercise_id);
		
		return getExerciseEntries($date);
	}

	public function exerciseSeries () {
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		insertExerciseSeries($name);
		return getExerciseSeries();
	}

	public function tagInExercise () {
		include(app_path() . '/inc/functions.php');
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		$tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
		insertExerciseTag($exercise_id, $tag_id);
		return getExercises();
	}

	public function tagsInExercise () {
		//deletes all tags then adds the correct tags
		include(app_path() . '/inc/functions.php');
		$exercise_id = json_decode(file_get_contents('php://input'), true)["exercise_id"];
		$tags = json_decode(file_get_contents('php://input'), true)["tags"];
		deleteTagsFromExercise($exercise_id);
		insertTagsInExercise($exercise_id, $tags);
		return getExercises();
	}

	public function quickRecipe () {
		include(app_path() . '/inc/functions.php');
		$recipe_name = json_decode(file_get_contents('php://input'), true)["recipe_name"];
		$contents = json_decode(file_get_contents('php://input'), true)["contents"];
		$steps = json_decode(file_get_contents('php://input'), true)["steps"];
		$check_similar_names = json_decode(file_get_contents('php://input'), true)["check_similar_names"];

		// Debugbar::info('recipe_name: ' . $recipe_name);
		// Debugbar::info('check_similar_names: ' . $check_similar_names);
		// Debugbar::info('contents', $contents);
		// Debugbar::info('steps', $steps);
		
		$similar_names = insertQuickRecipe($recipe_name, $contents, $steps, $check_similar_names);

		if ($similar_names) {
			return array(
				'similar_names' => $similar_names
			);
		}
		else {
			return array(
				'recipes' => getRecipes(),
				'foods_with_units' => getAllFoodsWithUnits(),
				'food_units' => getFoodUnits()
			);
		}	
	}

	public function journalEntry () {
		//inserts or updates journal entry
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$text = json_decode(file_get_contents('php://input'), true)["text"];
		insertOrUpdateJournalEntry($date, $text);
		Debugbar::info('text: ' . $text);

		$journal_entry = getJournalEntry($date);
		Debugbar::info('journal_entry: ', $journal_entry);
		return $journal_entry;
	}

	public function menuEntry () {
		include(app_path() . '/inc/functions.php');
		$data = json_decode(file_get_contents('php://input'), true);
		$date = $data['date'];
		insertMenuEntry($data);

		return getFoodEntries($date);
	}

	public function recipeEntry () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];
		$recipe_contents = json_decode(file_get_contents('php://input'), true)["recipe_contents"];

		insertRecipeEntry($date, $recipe_id, $recipe_contents);
		return getFoodEntries($date);
	}

	public function exerciseEntry () {
		include(app_path() . '/inc/functions.php');
		$data = json_decode(file_get_contents('php://input'), true);
		$date = $data['date'];
		insertExerciseEntry($data);

		return getExerciseEntries($date);
	}

	public function food () {
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		insertFood($name);

		return getAllFoodsWithUnits();
	}

	public function recipe () {
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		insertRecipe($name);
		return getRecipes();
	}

	public function exercise () {
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		$description = json_decode(file_get_contents('php://input'), true)["description"];
		insertExercise($name, $description);
		return getExercises();
	}

	public function exerciseUnit () {
		include(app_path() . '/inc/functions.php');
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		insertExerciseUnit($name);
		return getExerciseUnits();
	}

	public function foodUnit () {

	}

	public function exerciseEntries () {
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$sql = "INSERT INTO exercise_entries (date, exercise, quantity) VALUES ('$date', '$id', $quantity);";
	}

	public function foodIntoRecipe () {
		include(app_path() . '/inc/functions.php');
		$data = json_decode(file_get_contents('php://input'), true);
		$recipe_id = $data['recipe_id'];
		
		insertFoodIntoRecipe($recipe_id, $data);
		return getRecipeContents($recipe_id);
	}

	public function recipeEntries () {
		$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
		$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];

		$sql = "INSERT INTO food_recipe (recipe_id, food_id, quantity, unit) VALUES ($recipe_id, $food_id, $quantity, $unit_id);";
	}

	public function unitInCalories () {
		include(app_path() . '/inc/functions.php');
		$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
		$unit_id = json_decode(file_get_contents('php://input'), true)["unit_id"];
		insertUnitInCalories($food_id, $unit_id);
		return getFoodInfo($food_id);
	}

	public function weight () {
		include(app_path() . '/inc/functions.php');
		$date = json_decode(file_get_contents('php://input'), true)["date"];
		$weight = json_decode(file_get_contents('php://input'), true)["weight"];

		if (getWeight($date)) {
			//This date already has a weight entry. We are updating, not inserting.
			updateWeight($date, $weight);
		}
		else {
			//we are inserting
			insertWeight($date, $weight);
		}
		return getWeight($date);
	}

	public function item () {
		include(app_path() . '/inc/functions.php');
		$table = json_decode(file_get_contents('php://input'), true)["table"];
		$name = json_decode(file_get_contents('php://input'), true)["name"];
		$sql = "INSERT INTO $table (name) VALUES ('$name');";
	}
}
