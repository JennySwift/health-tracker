<?php



// ========================================================================
// ========================================================================
// =================================select=================================
// ========================================================================
// ========================================================================

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
		->select('food_id', 'foods.name AS food_name', 'food_entries.id AS entry_id', 'food_units.id AS unit_id', 'food_units.name AS unit_name', 'quantity', 'recipes.name AS recipe_name')
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
    		"recipe_name" => $recipe_name
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
			}
			else {
				$default_unit = false;
				// $default_unit_id = '';
				// $default_unit_name = '';
				// $default_unit_calories = '';
			}

			$units[] = array(
				"id" => $unit_id,
				"name" => $unit_name,
				"calories" => $calories,
				"default_unit" => $default_unit
			);
		}
	    
	    $food = array(
			"id" => $food_id,
			"name" => $food_name
			// "default_unit_id" => $default_unit_id,
			// "default_unit_name" => $default_unit_name,
			// "default_unit_calories" => $default_unit_calories
	    );

	    if (isset($default_unit_id)) {
	    	$food['default_unit_id'] = $default_unit_id;
	    }
	    if (isset($default_unit_id)) {
	    	$food['default_unit_name'] = $default_unit_name;
	    }
	    if (isset($default_unit_id)) {
	    	$food['default_unit_calories'] = $default_unit_calories;
	    }

	    $all_foods_with_units[] = array(
	    	"food" => $food,
	    	"units" => $units
	    );
	}

	// Debugbar::info('all_foods_with_units', $all_foods_with_units);
    
	return $all_foods_with_units;
}

function getFoodUnits () {
    $food_units = DB::table('food_units')
    	->where('user_id', Auth::user()->id)
    	->select('id', 'name')
    	->get();

    return $food_units;
}

function getExerciseUnits () {
    $result = DB::table('exercise_units')
    	->where('user_id', Auth::user()->id)
    	->select('id', 'name')
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
			"unit_name" => $unit_name,
			"unit_id" => $unit_id,
			"calories" => $calories,
			"default_unit" => $default_unit
		);
	}
    
	return $assoc_units;
}

function getFoodInfo ($food_id) {
	$default_unit = getDefaultUnit($food_id);

	$food_units = getFoodUnits();
	$assoc_units = getAssocUnits($food_id);
	$units = array();

	//checking to see if the unit has already been given to a food, so that it appears checked.
	foreach ($food_units as $food_unit) {
		$unit_id = $food_unit->id;
		$unit_name = $food_unit->name;
		$match = 0;

		foreach ($assoc_units as $assoc_unit) {
			$assoc_unit_id = $assoc_unit['unit_id'];
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
		->get();
	

	$recipes = array();
	foreach ($rows as $row) {
		$recipe_name = $row->name;
		$recipe_id = $row->id;

		$recipes[] = array(
			"name" => $recipe_name,
			"id" => $recipe_id
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

// =================================exercise=================================

function getExerciseEntries ($date) {
	$exercise_entries = DB::table('exercise_entries')
		->where('date', $date)
		->where('exercise_entries.user_id', Auth::user()->id)
		->join('exercises', 'exercise_entries.exercise_id', '=', 'exercises.id')
		->join('exercise_units', 'exercise_entries.exercise_unit_id', '=', 'exercise_units.id')
		->select('exercise_id', 'quantity', 'exercises.name', 'exercise_units.name AS unit_name', 'exercise_entries.id AS entry_id')
		->get();

	return $exercise_entries;
}

function getExercises () {
    $exercises = DB::table('exercises')
    	->where('exercises.user_id', Auth::user()->id)
    	->leftJoin('exercise_units', 'default_exercise_unit_id', '=', 'exercise_units.id')
    	->select('exercises.id', 'exercises.name', 'default_exercise_unit_id', 'default_quantity', 'exercise_units.name AS default_exercise_unit_name')
    	->orderBy('name', 'asc')
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

function insertNewExerciseTag ($name) {
	DB::table('exercise_tags')
		->insert([
			'name' => $name,
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

function insertTagsInExercise ($exercise_id, $tags) {
	foreach ($tags as $tag) {
		$tag_id = $tag['id'];
		insertExerciseTag($exercise_id, $tag_id);
	}
}

// ==============================quick recipe==============================

function insertQuickRecipe ($recipe_name, $contents) {
	//insert recipe into recipes table
	$recipe_id = insertQuickRecipeRecipe($recipe_name);

	//$contents needs to have: food_name, unit_name, quantity, maybe description
	foreach ($contents as $item) {
		$food_name = $item['food_name'];
		$unit_name = $item['unit_name'];
		$quantity = $item['quantity'];

		if (isset($item['description'])) {
			$description = $item['description'];
		}
		else {
			$description = null;
		}
		

		//check if the food exists
		$count = DB::table('foods')
			->where('name', $food_name)
			->where('user_id', Auth::user()->id)
			->count();

		if ($count < 1) {
			//the food does not yet exist so we need to create it
			$food_id = DB::table('foods')
				->insertGetId([
					'name' => $food_name,
					'user_id' => Auth::user()->id
				]);
		}
		else {
			//the food exists. retrieve the id of the food
			$food_id = DB::table('foods')
				->where('name', $food_name)
				->where('user_id', Auth::user()->id)
				->pluck('id');
		}

		//check if the unit exists
		$count = DB::table('food_units')
			->where('name', $unit_name)
			->where('user_id', Auth::user()->id)
			->count();

		if ($count < 1) {
			//the unit does not yet exist so we need to create it
			$unit_id = DB::table('food_units')
				->insertGetId([
					'name' => $unit_name,
					'user_id' => Auth::user()->id
				]);
		}
		else {
			//the unit exists. retrieve the id of the unit
			$unit_id = DB::table('food_units')
				->where('name', $unit_name)
				->where('user_id', Auth::user()->id)
				->pluck('id');
		}

		//insert the item into food_recipe table
		DB::table('food_recipe')
			->insert([
				'recipe_id' => $recipe_id,
				'food_id' => $food_id,
				'unit_id' => $unit_id,
				'quantity' => $quantity,
				'description' => $description,
				'user_id' => Auth::user()->id	
			]);	
	}
}

function insertQuickRecipeRecipe ($name) {
	//insert recipe into recipes table and retrieve the id
	$id = DB::table('recipes')
		->insertGetId([
			'name' => $name,
			'user_id' => Auth::user()->id
		]);

	return $id;
}

// function insertQuickRecipeFoods ($foods) {
// 	foreach ($foods as $food) {
// 		$count = DB::table('foods')
// 			->where('name', $food)
// 			->where('user_id', Auth::user()->id)
// 			->count();

// 		if ($count < 1) {
// 			//the food does not yet exist so we need to create it
// 			DB::table('foods')
// 				->insert([
// 					'name' => $food,
// 					'user_id' => Auth::user()->id
// 				]);
// 		}
// 	}
// }

// ============================end quick recipe============================

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

function insertFoodIntoRecipe ($data) {
	DB::table('food_recipe')
		->insert([
			'recipe_id' => $data['recipe_id'],
			'food_id' => $data['food_id'],
			'unit_id' => $data['unit_id'],
			'quantity' => $data['quantity'],
			'user_id' => Auth::user()->id
		]);
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
		$food_id = $item['food_id'];
		$quantity = $item['quantity'];
		$unit_id = $item['unit_id'];

		DB::table('food_entries')->insert([
			'date' => $date,
			'food' => $food,
			'quantity' => $quantity,
			'unit_id' => $unit_id,
			'recipe_id' => $recipe_id
		]);
	}
}

function insertMenuEntry ($data) {
	$date = $data['date'];
	$type = $data['type'];
	$new_entry = $data['new_entry'];

	if ($type === 'food') {
		DB::table('food_entries')->insert([
			'date' => $date,
			'food_id' => $new_entry['id'],
			'quantity' => $new_entry['quantity'],
			'unit_id' => $new_entry['unit_id'],
			'user_id' => Auth::user()->id
		]);
	}
	elseif ($type === 'recipe') {
		$recipe_contents = json_decode(file_get_contents('php://input'), true)["recipe_contents"];

		insertRecipeEntry($date, $id, $recipe_contents);
	}
}

function insertExerciseEntry ($data) {
	$date = $data['date'];
	$new_entry = $data['new_entry'];

	DB::table('exercise_entries')->insert([
		'date' => $date,
		'exercise_id' => $new_entry['id'],
		'quantity' => $new_entry['quantity'],
		'exercise_unit_id' => $new_entry['unit_id'],
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

function insertExercise ($name) {
	DB::table('exercises')->insert([
		'name' => $name,
		'user_id' => Auth::user()->id
	]);
}

function insertExerciseUnit ($name) {
	DB::table('exercise_units')->insert([
		'name' => $name,
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

function updateDefaultExerciseQuantity ($id, $quantity) {
	DB::table('exercises')
		->where('id', $id)
		->update([
			'default_quantity' => $quantity
		]);
}

function updateWeight ($date, $weight) {
	DB::table('weight')
		->where('date', $date)
		->where('user_id', Auth::user()->id)
		->update([
			'weight' => $weight
		]);
}

function updateDefaultExerciseUnit ($exercise_id, $default_exercise_unit_id) {
	DB::table('exercises')
		->where('id', $exercise_id)
		->update([
			'default_exercise_unit_id' => $default_exercise_unit_id
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

function deleteTagFromExercise ($exercise_id, $tag_id) {
	DB::table('exercise_tag')
		->where('exercise_id', $exercise_id)
		->where('tag_id', $tag_id)
		->delete();
}

function deleteTagsFromExercise ($exercise_id) {
	DB::table('exercise_tag')
		->where('exercise_id', $exercise_id)
		->delete();
}

function deleteExerciseTag ($id) {
	DB::table('exercise_tags')
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

function autocompleteExercise ($exercise) {
	$exercise = '%' . $exercise . '%';
	$exercises = DB::table('exercises')
		->where('name', 'LIKE', $exercise)
		->where('user_id', Auth::user()->id)
		->select('id', 'name', 'default_exercise_unit_id', 'default_quantity')
		->get();
    
	return $exercises;
}

function autocompleteFood ($typing) {
	$typing = '%' . $typing . '%';
	$foods = DB::table('foods')
		->where('name', 'LIKE', $typing)
		->where('user_id', Auth::user()->id)
		->select('id', 'name')
		->get();
    
	return $foods;
}

// ========================================================================
// ========================================================================
// ==============================other==============================
// ========================================================================
// ========================================================================

function getDaysAgo ($date) {
	$date = new DateTime($date);
	$diff = new DateInterval('P7D');
	$date = $date->sub($diff);
	return $date;
}
?>