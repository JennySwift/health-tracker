<?php



// ========================================================================
// ========================================================================
// =================================select=================================
// ========================================================================
// ========================================================================

function getWeight($date) {
	// Debugbar::info('hello');
	$weight = DB::table('weight')
		->where('date', $date)
		->where('user_id', Auth::user()->id)
		->pluck('weight');
	return $weight;
	// return 'hello from getWeight';
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

	$calories_for_the_day = getCaloriesForTheDay($date, "day");
	$calories_for_the_week = getCaloriesForTheDay($date, "week");

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
    $calories_for_the_day = number_format($calories_for_the_day, 2);
    $calories_for_the_week = number_format($calories_for_the_week, 2);

    $array = array(
    	"food_entries" => $food_entries,
    	"calories_for_the_day" => $calories_for_the_day,
    	"calories_for_the_week" => $calories_for_the_week
    );
    return $array;
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
			}

			$units[] = array(
				"unit_name" => $unit_name,
				"unit_id" => $unit_id,
				"calories" => $calories,
				"default_unit" => $default_unit
			);
		}
	    
	    $food = array(
			"food_id" => $food_id,
			"food_name" => $food_name,
			"default_unit_id" => $default_unit_id,
			"default_unit_name" => $default_unit_name,
			"default_unit_calories" => $default_unit_calories
	    );

	    $all_foods_with_units[] = array(
	    	"food" => $food,
	    	"units" => $units
	    );
	}

	// Debugbar::info('all_foods_with_units', $all_foods_with_units);
    
	return $all_foods_with_units;
}

function getFoodUnits ($db) {
    $sql = "SELECT * FROM food_units;";
    $sql_result = $db->query($sql);

	$array = array();
    while($row = $sql_result->fetch(PDO::FETCH_ASSOC)) {
    	$unit_id = $row['id'];
        $unit_name = $row['name'];

        $array[] = array(
        	"unit_name" => $unit_name,
        	"unit_id" => $unit_id
        );
    }
    return $array;
}

function getDefaultUnit ($db, $food_id) {
	$sql = "SELECT unit_id FROM calories WHERE default_unit = 'yes' AND food_id = $food_id;";
    $sql_result = $db->query($sql);
	$unit_id = $sql_result->fetchColumn();
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

function getCaloriesForTheDay ($date, $period) {
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

// function getAssocUnits () {
// 	$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];

// 	$array = getAssocUnits($db, $food_id);
// }

// function getAssocUnits ($db, $food_id) {
//     $sql = "SELECT food_units.name, food_units.id, calories, default_unit FROM calories JOIN foods ON food_id = foods.id JOIN food_units ON calories.unit_id = food_units.id WHERE food_id = $food_id";

//     $sql_result = $db->query($sql);

// 	$array = array();
//     while($row = $sql_result->fetch(PDO::FETCH_ASSOC)) {
//         $unit_name = $row['name'];
//         $unit_id = $row['id'];
//         $calories = $row['calories'];
//         $default_unit = $row['default_unit'];

//         if ($default_unit === "yes") {
//         	$default_unit = true;
//         }
//         elseif ($default_unit === null) {
//         	$default_unit = false;
//         }

//         $array[] = array(
//         	"unit_name" => $unit_name,
//         	"unit_id" => $unit_id,
//         	"calories" => $calories,
//         	"default_unit" => $default_unit
//         );
//     }
// 	return $array;
// }

// function getFoodAndAssocUnits () {
// 	$food_id = json_decode(file_get_contents('php://input'), true)["food_id"];
// 	$default_unit = getDefaultUnit($db, $food_id);

// 	$food_units = getFoodUnits($db);
// 	$assoc_units = getAssocUnits($db, $food_id);



// 	//checking to see if the unit has already been given to a food, so that it appears checked.
// 	foreach ($food_units as $food_unit) {
// 		$unit_id = $food_unit['unit_id'];
// 		$unit_name = $food_unit['unit_name'];
// 		$match = 0;

// 		foreach ($assoc_units as $assoc_unit) {
// 			$assoc_unit_id = $assoc_unit['unit_id'];
// 			$calories = $assoc_unit['calories'];

// 			if ($unit_id == $assoc_unit_id) {
// 				$match++;
// 			}
// 		}
// 		if ($match === 1) {
// 			$calories = getCalories($db, $food_id, $unit_id);

// 			if ($unit_id === $default_unit) {
// 				$array[] = array(
// 					"unit_id" => $unit_id,
// 					"unit_name" => $unit_name,
// 					"checked" => true,
// 					"default_unit" => true,
// 					"calories" => $calories
// 				);
// 			}
// 			else {
// 				$array[] = array(
// 					"unit_id" => $unit_id,
// 					"unit_name" => $unit_name,
// 					"checked" => true,
// 					"default_unit" => false,
// 					"calories" => $calories
// 				);
// 			}
// 		}
// 		else {
// 			$array[] = array(
// 				"unit_id" => $unit_id,
// 				"unit_name" => $unit_name,
// 				"checked" => false,
// 				"default_unit" => false
// 			);
// 		}
// 	}

// }

// =================================recipe=================================

function getRecipeList () {
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

function getExerciseEntries () {
	$date = json_decode(file_get_contents('php://input'), true)["date"];

    $sql = "SELECT exercise, quantity, name, exercise_entries.id AS entry_id FROM exercise_entries JOIN exercises ON exercise_entries.exercise = exercises.id WHERE date = '$date';";
    $sql_result = $db->query($sql);

	$array = array();
    while($row = $sql_result->fetch(PDO::FETCH_ASSOC)) {
        $exercise_id = $row['exercise'];
        $exercise_name = $row['name'];
        $quantity = $row['quantity'];
        $entry_id = $row['entry_id'];

        $array[] = array(
        	"exercise_id" => $exercise_id,
        	"exercise_name" => $exercise_name,
        	"quantity" => $quantity,
        	"entry_id" => $entry_id
        );
    }
}

function getExercises ($db) {
    $sql = "SELECT * FROM exercises;";
    $sql_result = $db->query($sql);

	$array = array();
    while($row = $sql_result->fetch(PDO::FETCH_ASSOC)) {
        $exercise_name = $row['name'];
        $exercise_id = $row['id'];

        $array[] = array(
        	"exercise_name" => $exercise_name,
        	"exercise_id" => $exercise_id
        );
    }
    return $array;
}

// ========================================================================
// ========================================================================
// =================================insert=================================
// ========================================================================
// ========================================================================

function insertRecipeEntry ($db, $date, $recipe_id, $recipe_contents) {
	foreach ($recipe_contents as $item) {
		$food_id = $item['food_id'];
		$quantity = $item['quantity'];
		$unit_id = $item['unit_id'];

		$sql = "INSERT INTO food_entries (date, food, quantity, unit, recipe_id) VALUES ('$date', $food_id, $quantity, $unit_id, $recipe_id);";
		$sql_result = $db->query($sql);

		require_once("../tools/FirePHPCore/FirePHP.class.php");
		ob_start();
		$firephp = FirePHP::getInstance(true);		
		$firephp->log($sql, 'sql');
	}
}

// ========================================================================
// ========================================================================
// =================================update=================================
// ========================================================================
// ========================================================================

// ========================================================================
// ========================================================================
// =================================delete=================================
// ========================================================================
// ========================================================================

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