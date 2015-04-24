<?php

include('quick-recipe-functions.php');
include('recipe-filter.php');

// ========================================================================
// ========================================================================
// =================================select=================================
// ========================================================================
// ========================================================================

function getId ($table, $name) {
	$id = DB::table($table)
		->where('name', $name)
		->where('user_id', Auth::user()->id)
		->pluck('id');

	return $id;
}

function getTagsForRecipe ($recipe_id) {
	$tags = DB::table('recipe_tag')
		->where('recipe_id', $recipe_id)
		->join('recipe_tags', 'tag_id', '=', 'recipe_tags.id')
		->select('tag_id as id', 'recipe_tags.name as name')
		->get();

	return $tags;
}

function getRecipeTags () {
	$recipe_tags = DB::table('recipe_tags')
		->where('user_id', Auth::user()->id)
		->orderBy('name', 'asc')
		->select('id', 'name')
		->get();

	return $recipe_tags;
}

function getDefaultExerciseQuantity ($exercise_id) {
	$quantity = DB::table('exercises')
		->where('id', $exercise_id)
		->pluck('default_quantity');

	return $quantity;
}

function getDefaultExerciseUnitId ($exercise_id) {
	$default = DB::table('exercises')
		->where('id', $exercise_id)
		->pluck('default_exercise_unit_id');

	return $default;
}

function getExerciseSeries () {
	$exercise_series = DB::table('exercise_series')
		->where('user_id', Auth::user()->id)
		->select('name', 'id')
		->orderBy('name', 'asc')
		->get();

	foreach ($exercise_series as $series) {
		$series_id = $series->id;
		$series->workouts = getSeriesWorkouts($series_id);
	}

	return $exercise_series;
}

function getSeriesWorkouts ($series_id) {
	//find which workouts the series is part of
	$workouts = DB::table('series_workout')
		->where('series_id', $series_id)
		->join('workouts', 'workout_id', '=', 'workouts.id')
		->select('workouts.name', 'workouts.id')
		->get();

	foreach ($workouts as $workout) {
		$workout_id = $workout->id;
		$workout->contents = getWorkoutContents($workout_id);
	}

	return $workouts;
}

function getWorkoutContents ($workout_id) {
	$workout_contents = DB::table('series_workout')
		->where('workout_id', $workout_id)
		->join('exercise_series', 'series_id', '=', 'exercise_series.id')
		->select('series_id', 'exercise_series.name')
		->orderBy('exercise_series.name', 'asc')
		->get();

	return $workout_contents;	
}

function getWorkouts () {
	$workouts = DB::table('workouts')
		->where('user_id', Auth::user()->id)
		->select('id', 'name')
		->get();

	//get all the series that are in the workout
	foreach ($workouts as $workout) {
		$workout_id = $workout->id;
		$workout->contents = getWorkoutContents($workout_id);
	}

	return $workouts;
}

function countItem ($table, $name) {
	$count = DB::table($table)
		->where('name', $name)
		->where('user_id', Auth::user()->id)
		->count();

	return $count;
}

function getExerciseTags () {
	//gets all exercise tags
	$tags = DB::table('exercise_tags')
		->where('user_id', Auth::user()->id)
		->select('id', 'name')
		->get();

	return $tags;
}

function getTagsForExercise ($exercise_id) {
	//gets tags associated with each exercise
	$tags = DB::table('exercise_tag')
		->where('exercise_id', $exercise_id)
		->join('exercise_tags', 'exercise_tag.tag_id', '=', 'exercise_tags.id')
		->select('exercise_tags.id', 'name')
		->get();

	return $tags;
}

function getWeight($date) {
	$weight = DB::table('weight')
		->where('date', $date)
		->where('user_id', Auth::user()->id)
		->pluck('weight');

	if (!$weight) {
		$weight = 0;
	}
	return $weight;
}

function getJournalEntry ($date) {
	$entry = DB::table('journal_entries')
		->where('date', $date)
		->where('user_id', Auth::user()->id)
		->select('id', 'text')
		->first();

	//for some reason I would get an error if I didn't do this:
	if (isset($entry)) {
		$response = array(
			'id' => $entry->id,
			'text' => $entry->text
		);
	}
	else {
		$response = array();
	}
	

	// if (!isset($entry)) {
	// 	//so that $scope.journal_entry isn't null in js
	// 	$entry = array(
	// 		'id' => '',
	// 		'text' => ''
	// 	);
	// }
	// Debugbar::info('journal entry: ', $entry);
	// $entry = nl2br($entry);
	Debugbar::info('response: ', $response);
	return $response;
}

// =================================food=================================

function getFoods () {
	$query = DB::table('foods')
		->where('user_id', Auth::user()->id)
		->orderBy('name', 'asc')->get();

	$foods = array();
	foreach ($query as $food) {
		$food_id = $food->id;
		$food_name = $food->name;
		
		$foods[] = array(
			"id" => $food_id,
			"name" => $food_name
		);
	}

	// Debugbar::info($foods);

	return $foods;
}

function getFoodEntries ($date) {
	$rows = DB::table('food_entries')
		->join('foods', 'food_entries.food_id', '=', 'foods.id')
		->join('food_units', 'food_entries.unit_id', '=', 'food_units.id')
		->leftJoin('recipes', 'food_entries.recipe_id', '=', 'recipes.id')
		->where('date', $date)
		->where('food_entries.user_id', Auth::user()->id)
		->select('food_id', 'foods.name AS food_name', 'food_entries.id AS entry_id', 'food_units.id AS unit_id', 'food_units.name AS unit_name', 'quantity', 'recipes.name AS recipe_name', 'recipes.id AS recipe_id')
		->get();
    

	$food_entries = array();

    foreach ($rows as $row) {
    	$food_id = $row->food_id;
    	$food_name = $row->food_name;
    	$quantity = $row->quantity;
    	$entry_id = $row->entry_id;
    	$unit_id = $row->unit_id;
    	$unit_name = $row->unit_name;
    	$recipe_name = $row->recipe_name;
    	$recipe_id = $row->recipe_id;

    	$calories_for_item = getCalories($food_id, $unit_id);
    	$calories_for_quantity = getCaloriesForQuantity($calories_for_item, $quantity);
    	$calories_for_quantity = number_format($calories_for_quantity, 2);

    	$food_entries[] = array(
    		"food_id" => $food_id,
    		"food_name" => $food_name,
    		"quantity" => $quantity,
    		"entry_id" => $entry_id,
    		"unit_id" => $unit_id,
    		"unit_name" => $unit_name,
    		"calories" => $calories_for_quantity,
    		"recipe_name" => $recipe_name,
    		"recipe_id" => $recipe_id
    	);
    }

    return $food_entries;
}

function getAllFoodsWithUnits () {
	$foods = getFoods();
	$all_foods_with_units = array();

	foreach ($foods as $food) {
		$food_id = $food['id'];
		$food_name = $food['name'];

	    $food = array(
			"id" => $food_id,
			"name" => $food_name
	    );

		$rows = DB::table('calories')
			->join('foods', 'food_id', '=', 'foods.id')
			->join('food_units', 'calories.unit_id', '=', 'food_units.id')
			->where('food_id', $food_id)
			->select('food_units.name', 'food_units.id', 'calories', 'default_unit')
			->get();

		$units = array();
		foreach ($rows as $row) {
			$unit_name = $row->name;
			$unit_id = $row->id;
			$calories = $row->calories;
			$default_unit = $row->default_unit;

			if ($default_unit === 1) {
				$default_unit = true;
				$default_unit_id = $unit_id;
				$default_unit_name = $unit_name;
				$default_unit_calories = $calories;

				$food['default_unit_id'] = $default_unit_id;
				$food['default_unit_name'] = $default_unit_name;
				$food['default_unit_calories'] = $default_unit_calories;
			}
			else {
				$default_unit = false;
			}

			$units[] = array(
				"id" => $unit_id,
				"name" => $unit_name,
				"calories" => $calories,
				"default_unit" => $default_unit
			);
		}

	    $all_foods_with_units[] = array(
	    	"food" => $food,
	    	"units" => $units
	    );
	}
    
	return $all_foods_with_units;
}

function getFoodUnits () {
    $food_units = DB::table('food_units')
    	->where('user_id', Auth::user()->id)
    	->select('id', 'name')
    	->orderBy('name', 'asc')
    	->get();

    return $food_units;
}

function getExerciseUnits () {
    $result = DB::table('exercise_units')
    	->where('user_id', Auth::user()->id)
    	->select('id', 'name')
    	->orderBy('name', 'asc')
    	->get();

    //so that it is an array, not an object
    $exercise_units = array();
    foreach ($result as $unit) {
    	$exercise_units[] = $unit;
    }

    return $exercise_units;
}

function getDefaultUnit ($food_id) {
	$unit_id = DB::table('calories')
		->where('default_unit', 1)
		->where('food_id', $food_id)
		->where('user_id', Auth::user()->id)
		->pluck('unit_id');
	
	return $unit_id;
}

function getCalories ($food_id, $unit_id) {
	$calories = DB::table('calories')
		->where('food_id', $food_id)
		->where('unit_id', $unit_id)
		->pluck('calories');
	
	return $calories;
}

function getCaloriesForQuantity ($calories_for_item, $quantity) {
	$calories_for_quantity = $calories_for_item * $quantity;
	return $calories_for_quantity;
}

function getCaloriesForTimePeriod ($date, $period) {
	$calories_for_period = 0;

	if ($period === "day") {
		$rows = DB::table('food_entries')
			->join('foods', 'food_entries.food_id', '=', 'foods.id')
			->join('food_units', 'food_entries.unit_id', '=', 'food_units.id')
			->where('date', $date)
			->where('food_entries.user_id', Auth::user()->id)
			->select('food_id', 'food_units.id AS unit_id', 'quantity')
			->get();
	}
	elseif ($period === "week") {
		$a_week_ago = getDaysAgo($date);
		$rows = DB::table('food_entries')
			->join('foods', 'food_entries.food_id', '=', 'foods.id')
			->join('food_units', 'food_entries.unit_id', '=', 'food_units.id')
			->where('date', '>=', $a_week_ago)
			->where('date', '<=', $date)
			->where('food_entries.user_id', Auth::user()->id)
			->select('food_id', 'food_units.id AS unit_id', 'quantity')
			->get();
	}

	foreach ($rows as $row) {
		$food_id = $row->food_id;
		$unit_id = $row->unit_id;
		$quantity = $row->quantity;

		$calories_for_item = getCalories($food_id, $unit_id);
		$calories_for_quantity = getCaloriesForQuantity($calories_for_item, $quantity);
		$calories_for_period += $calories_for_quantity;
	}

	if ($period === "week") {
		$calories_for_period /= 7;
	}
	return $calories_for_period;
}

function getAssocUnits ($food_id) {
	$rows = DB::table('calories')
		->where('food_id', $food_id)
		->join('foods', 'food_id', '=', 'foods.id')
		->join('food_units', 'calories.unit_id', '=', 'food_units.id')
		->select('food_units.name', 'food_units.id', 'calories', 'default_unit')
		->get();
   

	$assoc_units = array();
	foreach ($rows as $row) {
		$unit_name = $row->name;
		$unit_id = $row->id;
		$calories = $row->calories;
		$default_unit = $row->default_unit;

		if ($default_unit === 1) {
			$default_unit = true;
		}
		else {
			$default_unit = false;
		}

		$assoc_units[] = array(
			"name" => $unit_name,
			"id" => $unit_id,
			"calories" => $calories,
			"default" => $default_unit
		);
	}
    
	return $assoc_units;
}

function getFoodInfo ($food_id) {
	$default_unit = getDefaultUnit($food_id);

	$food_units = getFoodUnits();
	$assoc_units = getAssocUnits($food_id);
	$units = array();
	Debugbar::info('food_units', $food_units);
	Debugbar::info('assoc_units', $assoc_units);

	//checking to see if the unit has already been given to a food, so that it appears checked.
	foreach ($food_units as $food_unit) {
		$unit_id = $food_unit->id;
		$unit_name = $food_unit->name;
		$match = 0;

		foreach ($assoc_units as $assoc_unit) {
			$assoc_unit_id = $assoc_unit['id'];
			$calories = $assoc_unit['calories'];

			if ($unit_id == $assoc_unit_id) {
				$match++;
			}
		}
		if ($match === 1) {
			$calories = getCalories($food_id, $unit_id);

			if ($unit_id === $default_unit) {
				$units[] = array(
					"id" => $unit_id,
					"name" => $unit_name,
					"checked" => true,
					"default_unit" => true,
					"calories" => $calories
				);
			}
			else {
				$units[] = array(
					"id" => $unit_id,
					"name" => $unit_name,
					"checked" => true,
					"default_unit" => false,
					"calories" => $calories
				);
			}
		}
		else {
			$units[] = array(
				"id" => $unit_id,
				"name" => $unit_name,
				"checked" => false,
				"default_unit" => false
			);
		}
	}

	return $units;
}

// =================================recipe=================================

function getRecipes () {
	$rows = DB::table('recipes')
		->where('user_id', Auth::user()->id)
		->select('id', 'name')
		->orderBy('name', 'asc')
		->get();
	

	$recipes = array();
	foreach ($rows as $row) {
		$recipe_id = $row->id;
		$recipe_name = $row->name;
		$tags = getTagsForRecipe($recipe_id);
		
		$recipes[] = array(
			"id" => $recipe_id,
			"name" => $recipe_name,
			"tags" => $tags
		);
	}
	return $recipes;
}

function getRecipeEntries () {
	$recipe_id = json_decode(file_get_contents('php://input'), true)["recipe_id"];

	$recipe_info = array(
		"id" => $recipe_id
	);
	$contents = getRecipeContents($recipe_id);
	
	$array = array(
		"recipe_info" => $recipe_info,
		"contents" => $contents
	);
}

function getRecipeContents ($recipe_id) {
	$recipe_contents = DB::table('food_recipe')
		->where('recipe_id', $recipe_id)
		->join('foods', 'food_recipe.food_id', '=', 'foods.id')
		->join('food_units', 'food_recipe.unit_id', '=', 'food_units.id')
		->select('food_recipe.id', 'food_recipe.description', 'foods.name AS food_name', 'food_units.name AS unit_name', 'recipe_id', 'food_id', 'quantity', 'unit_id')
		->get();

	foreach ($recipe_contents as $item) {
		$food_id = $item->food_id;
		$assoc_units = getAssocUnits($food_id);
		$item->assoc_units = $assoc_units;
	}
	
	return $recipe_contents;
}

function getRecipeSteps ($recipe_id) {
	$steps = DB::table('recipe_methods')
		->where('recipe_id', $recipe_id)
		->select('step', 'text')
		->get();

	return $steps;
}

// =================================exercise=================================

// function getExerciseEntries ($date) {
// 	$exercise_entries = DB::table('exercise_entries')
// 		->where('date', $date)
// 		->where('exercise_entries.user_id', Auth::user()->id)
// 		->join('exercises', 'exercise_entries.exercise_id', '=', 'exercises.id')
// 		->join('exercise_units', 'exercise_entries.exercise_unit_id', '=', 'exercise_units.id')
// 		->select('exercise_id', 'quantity', 'exercises.name', 'exercise_units.name AS unit_name', 'exercise_entries.id AS entry_id')
// 		->orderBy('exercises.name', 'asc')
// 		->get();

// 	// //get the total reps (or other unit) done of each exercise
// 	// foreach ($exercise_entries as $entry) {
// 	// 	$total = 0;
// 	// 	$quantity = $entry->quantity;
// 	// 	$unit = $entry->unit_name;
// 	// }

// 	return $exercise_entries;
// }

function getExerciseEntries ($date) {
	$entries = DB::table('exercise_entries')
		->where('date', $date)
		->where('exercise_entries.user_id', Auth::user()->id)
		->join('exercises', 'exercise_entries.exercise_id', '=', 'exercises.id')
		->join('exercise_units', 'exercise_entries.exercise_unit_id', '=', 'exercise_units.id')
		->select('exercise_id', 'quantity', 'exercises.description', 'exercise_unit_id', 'exercises.name', 'exercise_units.name AS unit_name', 'exercise_units.id AS unit_id', 'exercise_entries.id AS entry_id')
		->orderBy('exercises.name', 'asc')
		->orderBy('unit_name', 'asc')
		->get();

	return compactExerciseEntries($entries, $date);
}

function getExerciseSets ($date, $exercise_id, $exercise_unit_id) {
	$sets = DB::table('exercise_entries')
		->where('date', $date)
		->where('exercise_id', $exercise_id)
		->where('exercise_unit_id', $exercise_unit_id)
		->where('user_id', Auth::user()->id)
		->count();

	return $sets;
}

function getTotalExerciseReps ($date, $exercise_id, $exercise_unit_id) {
	$total = DB::table('exercise_entries')
		->where('date', $date)
		->where('exercise_id', $exercise_id)
		->where('exercise_unit_id', $exercise_unit_id)
		->where('user_id', Auth::user()->id)
		->sum('quantity');

	return $total;
}

function compactExerciseEntries ($entries, $date) {
	$array = array();
	foreach ($entries as $entry) {
		$exercise_id = $entry->exercise_id;
		$exercise_unit_id = $entry->exercise_unit_id;
		$counter = 0;

		$total = getTotalExerciseReps($date, $exercise_id, $exercise_unit_id);

		$sets = getExerciseSets($date, $exercise_id, $exercise_unit_id);

		//check to see if the array already has the exercise entry so it doesn't appear as a new entry for each set of exercises
		foreach ($array as $item) {
			if ($item['name'] === $entry->name && $item['unit_name'] === $entry->unit_name) {
				//the exercise with unit already exists in the array so we don't want to add it again
				$counter++;
			}
		}
		if ($counter === 0) {
			$array[] = array(
				'exercise_id' => $entry->exercise_id,
				'name' => $entry->name,
				'description' => $entry->description,
				'quantity' => $entry->quantity,
				'unit_name' => $entry->unit_name,
				'sets' => $sets,
				'total' => $total,
				'unit_id' => $entry->unit_id,
				'default_exercise_unit_id' => getDefaultExerciseUnitId($exercise_id),
				// 'default_quantity' => $default_quantity
			);
		}	
	}
	return $array;
}

function getExercises () {
    $exercises = DB::table('exercises')
    	->where('exercises.user_id', Auth::user()->id)
    	->leftJoin('exercise_units', 'default_exercise_unit_id', '=', 'exercise_units.id')
    	->leftJoin('exercise_series', 'exercises.series_id', '=', 'exercise_series.id')
    	->select('exercises.id', 'exercises.name', 'exercises.description', 'exercises.step_number', 'exercise_series.name as series_name', 'default_exercise_unit_id', 'default_quantity', 'exercise_units.name AS default_exercise_unit_name')
    	->orderBy('series_name', 'asc')
    	->orderBy('step_number', 'asc')
    	->get();

    foreach ($exercises as $exercise) {
    	$id = $exercise->id;
    	$tags = getTagsForExercise($id);
    	$exercise->tags = $tags;
    }

    return $exercises;
}

// ========================================================================
// ========================================================================
// =================================insert=================================
// ========================================================================
// ========================================================================

function insertRecipeMethod ($recipe_id, $steps) {
	$step_number = 0;
	foreach ($steps as $step_text) {
		$step_number++;

		DB::table('recipe_methods')
			->insert([
				'recipe_id' => $recipe_id,
				'step' => $step_number,
				'text' => $step_text,
				'user_id' => Auth::user()->id
			]);
	}
}

function insertRecipeTag ($name) {
	DB::table('recipe_tags')
		->insert([
			'name' => $name,
			'user_id' => Auth::user()->id
		]);
}

function insertTagIntoRecipe ($recipe_id, $tag_id) {
	DB::table('recipe_tag')
		->insert([
			'recipe_id' => $recipe_id,
			'tag_id' => $tag_id,
			'user_id' => Auth::user()->id
		]);
}

function insertFood ($name) {
	DB::table('foods')->insert([
		'name' => $name,
		'user_id' => Auth::user()->id
	]);
}

function insertFoodIntoRecipe ($recipe_id, $data) {
	if (isset($data['description'])) {
		$description = $data['description'];
	}
	else {
		$description = null;
	}

	DB::table('food_recipe')
		->insert([
			'recipe_id' => $recipe_id,
			'food_id' => $data['food_id'],
			'unit_id' => $data['unit_id'],
			'quantity' => $data['quantity'],
			'description' => $description,
			'user_id' => Auth::user()->id
		]);
}

function insertExerciseTag ($exercise_id, $tag_id) {
	//inserts a tag into an exercise
	DB::table('exercise_tag')
		->insert([
			'exercise_id' => $exercise_id,
			'tag_id' => $tag_id,
			'user_id' => Auth::user()->id
		]);
}

function insertTagsIntoRecipe ($recipe_id, $tags) {
	foreach ($tags as $tag) {
		$tag_id = $tag['id'];
		insertTagIntoRecipe($recipe_id, $tag_id);
	}
}

function insertOrUpdateJournalEntry ($date, $text) {
	//check if an entry already exists
	$count = DB::table('journal_entries')
		->where('date', $date)
		->where('user_id', Auth::user()->id)
		->count();

	Debugbar::info('count: ' . $count);

	if ($count === 0) {
		//create a new entry
		DB::table('journal_entries')
			->insert([
				'date' => $date,
				'text' => $text,
				'user_id' => Auth::user()->id
			]);
	}
	else {
		//update existing entry
		DB::table('journal_entries')
			->where('date', $date)
			->where('user_id', Auth::user()->id)
			->update([
				'text' => $text
			]);
	}
	
}

function insertRecipe ($name) {
	DB::table('recipes')
		->insert([
			'name' => $name,
			'user_id' => Auth::user()->id
		]);
}

function insertRecipeEntry ($date, $recipe_id, $recipe_contents) {
	foreach ($recipe_contents as $item) {
		Debugbar::info('item', $item);
		$food_id = $item['food_id'];
		$quantity = $item['quantity'];
		$unit_id = $item['unit_id'];

		DB::table('food_entries')->insert([
			'date' => $date,
			'food_id' => $food_id,
			'quantity' => $quantity,
			'unit_id' => $unit_id,
			'recipe_id' => $recipe_id,
			'user_id' => Auth::user()->id
		]);
	}
}

function insertMenuEntry ($data) {
	$date = $data['date'];
	$new_entry = $data['new_entry'];

	DB::table('food_entries')->insert([
		'date' => $date,
		'food_id' => $new_entry['id'],
		'quantity' => $new_entry['quantity'],
		'unit_id' => $new_entry['unit_id'],
		'user_id' => Auth::user()->id
	]);
}

function insertUnitInCalories ($food_id, $unit_id) {
	DB::table('calories')
		->insert([
			'food_id' => $food_id,
			'unit_id' => $unit_id,
			'user_id' => Auth::user()->id
		]);
}

function insertWeight ($date, $weight) {
	DB::table('weight')
		->insert([
			'date' => $date,
			'weight' => $weight,
			'user_id' => Auth::user()->id
		]);
}

// ========================================================================
// ========================================================================
// =================================update=================================
// ========================================================================
// ========================================================================

function updateWeight ($date, $weight) {
	DB::table('weight')
		->where('date', $date)
		->where('user_id', Auth::user()->id)
		->update([
			'weight' => $weight
		]);
}

function updateCalories ($food_id, $unit_id, $calories) {
	DB::table('calories')
		->where('food_id', $food_id)
		->where('unit_id', $unit_id)
		->update([
			'calories' => $calories
		]);
}

function updateDefaultUnit ($food_id, $unit_id) {
	DB::table('calories')
		->where('food_id', $food_id)
		->where('default_unit', 1)
		->update([
			'default_unit' => 0
		]);

	DB::table('calories')
		->where('food_id', $food_id)
		->where('unit_id', $unit_id)
		->update([
			'default_unit' => 1
		]);	
}

// ========================================================================
// ========================================================================
// =================================delete=================================
// ========================================================================
// ========================================================================

function deleteRecipeEntry ($date, $recipe_id) {
	DB::table('food_entries')
		->where('date', $date)
		->where('recipe_id', $recipe_id)
		->delete();
}

function deleteRecipeMethod ($recipe_id) {
	DB::table('recipe_methods')
		->where('recipe_id', $recipe_id)
		->delete();
}

function deleteTagsFromRecipe ($recipe_id) {
	DB::table('recipe_tag')
		->where('recipe_id', $recipe_id)
		->delete();
}

function deleteRecipeTag ($id) {
	DB::table('recipe_tags')
		->where('id', $id)
		->delete();
}

function deleteFoodFromRecipe ($id) {
	DB::table('food_recipe')
		->where('id', $id)
		->delete();
}

function deleteRecipe ($id) {
	DB::table('recipes')
		->where('id', $id)
		->delete();
}

function deleteUnitFromCalories ($food_id, $unit_id) {
	DB::table('calories')
		->where('food_id', $food_id)
		->where('unit_id', $unit_id)
		->delete();
}

// ========================================================================
// ========================================================================
// ==============================autocomplete==============================
// ========================================================================
// ========================================================================

function autocompleteFood ($typing) {
	$typing = '%' . $typing . '%';
	$foods = DB::table('foods')
		->where('name', 'LIKE', $typing)
		->where('user_id', Auth::user()->id)
		->select('id', 'name')
		->get();
    
	return $foods;
}

function autocompleteMenu ($typing) {
	$typing = '%' . $typing . '%';
	
	$menu = DB::select("select * from (select id, name, 'food' as type from foods where name LIKE '$typing' and user_id = " . Auth::user()->id . " UNION select id, name, 'recipe' as type from recipes where name LIKE '$typing' and user_id = " . Auth::user()->id . ") as table1 order by table1.name asc");

	return $menu;
}

// ========================================================================
// ========================================================================
// ==============================other==============================
// ========================================================================
// ========================================================================

function convertDate ($date, $for) {
	$date = new DateTime($date);

	if ($for === 'user') {
		$date = $date->format('d/m/y');
	}
	elseif ($for === 'sql') {
		$date = $date->format('Y-m-d');
	}
	return $date;
}

function getDaysAgo ($date) {
	//I think this gets a date 7 days ago
	$date = new DateTime($date);
	$diff = new DateInterval('P7D');
	$date = $date->sub($diff);
	return $date;
}

function getHowManyDaysAgo ($date) {
	//to find out how many days ago a date was
	$now = new DateTime('now');
	$date = new DateTime($date);
	// Debugbar::info('now', $now);
	// Debugbar::info('date', $date);
	$diff = $now->diff($date);
	$days_ago = $diff->days;
	return $days_ago;
	// return 7;
}
?>