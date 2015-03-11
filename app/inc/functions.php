<?php



// ========================================================================
// ========================================================================
// =================================select=================================
// ========================================================================
// ========================================================================

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
    $exercise_units = DB::table('exercise_units')
    	->where('user_id', Auth::user()->id)
    	->select('id', 'name')
    	->get();

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
	$rows = DB::table('recipes')->get();
	

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
	$contents = getRecipeContents($db, $recipe_id);
	
	$array = array(
		"recipe_info" => $recipe_info,
		"contents" => $contents
	);
}

function getRecipeContents ($db, $recipe_id) {
	$sql = "SELECT recipe_entries.id, foods.name AS food_name, food_units.name AS unit_name, recipe_id, food_id, quantity, unit AS unit_id FROM recipe_entries JOIN foods ON recipe_entries.food_id = foods.id JOIN food_units ON recipe_entries.unit = food_units.id WHERE recipe_id = $recipe_id";
	$sql_result = $db->query($sql);

	$recipe_contents = array();
	while ($row = $sql_result->fetch(PDO::FETCH_ASSOC)) {
		$id = $row['id'];
		$food_id = $row['food_id'];
		$food_name = $row['food_name'];
		$unit_id = $row['unit_id'];
		$unit_name = $row['unit_name'];
		$quantity = $row['quantity'];

		$assoc_units = getAssocUnits($db, $food_id);

		$food = array(
			"id" => $id,
			"food_id" => $food_id,
			"food_name" => $food_name,
			"unit_id" => $unit_id,
			"unit_name" => $unit_name,
			"quantity" => $quantity,
			"assoc_units" => $assoc_units
		);

		$recipe_contents[] = $food;
	}
	return $recipe_contents;
}

// =================================exercise=================================

function getExerciseEntries ($date) {
	$exercise_entries = DB::table('exercise_entries')
		->where('date', $date)
		->where('exercise_entries.user_id', Auth::user()->id)
		->join('exercises', 'exercise_entries.exercise_id', '=', 'exercises.id')
		->select('exercise_id', 'quantity', 'name', 'exercise_entries.id AS entry_id')
		->get();

	return $exercise_entries;
}

function getExercises () {
    $exercises = DB::table('exercises')
    	->where('user_id', Auth::user()->id)
    	->select('id', 'name')
    	->get();

    return $exercises;
}

// ========================================================================
// ========================================================================
// =================================insert=================================
// ========================================================================
// ========================================================================

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

function autocompleteFood () {
	$food = json_decode(file_get_contents('php://input'), true)["food"];
	
    $sql = "SELECT * FROM foods WHERE name LIKE '%$food%';";

    $sql_result = $db->query($sql);

	$foods = array();
    while($row = $sql_result->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['id'];
        $name = $row['name'];

        $foods[] = array(
        	"food_id" => $id,
        	"food_name" => $name
        );
    }   
}

function autocompleteExercise () {
	$exercise = $_GET['exercise'];
	
    $sql = "SELECT * FROM exercises WHERE name LIKE '%$exercise%';";

    $sql_result = $db->query($sql);

	$exercises = array();
    while($row = $sql_result->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['id'];
        $name = $row['name'];

        $exercises[] = array(
        	"id" => $id,
        	"name" => $name
        );
    }
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