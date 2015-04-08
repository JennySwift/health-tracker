<?php

function insertQuickRecipe ($recipe_name, $contents, $steps, $check_similar_names) {
	$similar_names = array();
	$data_to_insert = array();
	//$index is so if a similar name is found, I know what index it is in the quick recipe array for the javascript.
	$index = -1;

	Debugbar::info('recipe_name: ' . $recipe_name);
	Debugbar::info('check_similar_names: ' . $check_similar_names);
	Debugbar::info('contents', $contents);
	Debugbar::info('steps', $steps);

	//$contents needs to have: food_name, unit_name, quantity, maybe description
	foreach ($contents as $item) {
		$food_name = $item['food_name'];
		$unit_name = $item['unit_name'];
		$quantity = $item['quantity'];
		$index++;

		if (isset($item['description'])) {
			$description = $item['description'];
		}
		else {
			$description = null;
		}

		if ($check_similar_names) {
			Debugbar::info('check_similar_names is true');
			$found = checkSimilarNames($food_name, 'foods');

			if ($found) {
				$similar_names['foods'][] = array(
					'specified_food' => array('name' => $food_name),
					'existing_food' => array('name' => $found),
					'checked' => $found,
					'index' => $index
				);
			}

			$found = checkSimilarNames($unit_name, 'food_units');

			if ($found) {
				$similar_names['units'][] = array(
					'specified_unit' => array('name' => $unit_name),
					'existing_unit' => array('name' => $found),
					'checked' => $found,
					'index' => $index
				);
			}
		}
		if (!$check_similar_names || count($similar_names) === 0) {
			Debugbar::info('check_similar_names is false or similar_names count is 0');
			Debugbar::info('similar_names count: ' . count($similar_names));
			//we can insert things now that no similar names were found, or we have already checked for similar names previously.
		
			//retrieve the id if the food exists, insert and retrieve the id if the food does not exist
			$food_id = insertFoodIfNotExists($food_name);
			//same for the unit
			$unit_id = insertUnitIfNotExists($unit_name);

			//add the item to the array for inserting when all items are in the array
			$data_to_insert[] = array(
				'food_id' => $food_id,
				'unit_id' => $unit_id,
				'quantity' => $quantity,
				'description' => $description
			);
		}
	}

	if (count($similar_names) > 0) {
		return $similar_names;
	}

	//insert recipe into recipes table
	$recipe_id = insertQuickRecipeRecipe($recipe_name);

	//insert the method for the recipe
	insertQuickRecipeMethod($recipe_id, $steps);

	Debugbar::info('data_to_insert', $data_to_insert);

	//insert the items into food_recipe table
	foreach ($data_to_insert as $item) {
		Debugbar::info('item', $item);
		Debugbar::info('recipe_id: ' . $recipe_id);
		//insert a row into food_recipe table
		insertFoodIntoRecipe($recipe_id, $item);

		//insert food and unit ids into calories table (if the row doesn't exist already in the table) so that the unit is an associated unit of the food
		$count = DB::table('calories')
			->where('food_id', $item['food_id'])
			->where('unit_id', $item['unit_id'])
			->where('user_id', Auth::user()->id)
			->count();

		if ($count === 0) {
			insertUnitInCalories($item['food_id'], $item['unit_id']);
		}	
	}
}

function insertFoodIfNotExists ($food_name) {
	$count = countItem('foods', $food_name);

	if ($count < 1) {
		//the food does not exist. create the new food.
		$food_id = DB::table('foods')
			->insertGetId([
				'name' => $food_name,
				'user_id' => Auth::user()->id
			]);
	}
	else {
		//the food exists. retrieve the id of the food
		$food_id = getId('foods', $food_name);
	}

	return $food_id;
}

function insertUnitIfNotExists ($unit_name) {
	$count = countItem('food_units', $unit_name);

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
		$unit_id = getId('food_units', $unit_name);
	}

	return $unit_id;
}

function checkSimilarNames ($name, $table) {
	$count = countItem($table, $name);

	if ($count < 1) {
		//the name does not exist

		if (substr($name, -3) === 'ies') {
			//the name ends in 'ies'. check if it's singular form exists.
			$similar_name = substr($name, 0, -3) . 'y';
			$found = pluckName($similar_name, $table);
		}
		elseif (substr($name, -1) === 'y') {
			//the name ends in 'y'. Check if it's plural form exists.
			$similar_name = substr($name, 0, -1) . 'ies';
			$found = pluckName($similar_name, $table);
		}

		elseif (substr($name, -1) === 's' && !isset($found)) {
			//the name ends in s. check if its singular form is in the database
			$similar_name = substr($name, 0, -1);
			$found = pluckName($similar_name, $table);

			//if nothing was found, check if its plural form is in the database
			if (!isset($found)) {
				$similar_name = $name . 'es';
				$found = pluckName($similar_name, $table);
			}
		}

		//check if it's plural form exists if no singular forms were found
		if (!isset($found)) {
			$similar_name = $name . 's';
			$found = pluckName($similar_name, $table);
		}
	}
	if (isset($found)) {
		return $found;
	}
}

function pluckName ($name, $table) {
	$name = DB::table($table)
		->where('name', $name)
		->where('user_id', Auth::user()->id)
		->pluck('name');

	return $name;
}

function insertQuickRecipeMethod ($recipe_id, $steps) {
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

function insertQuickRecipeRecipe ($name) {
	//insert recipe into recipes table and retrieve the id
	$id = DB::table('recipes')
		->insertGetId([
			'name' => $name,
			'user_id' => Auth::user()->id
		]);

	return $id;
}

?>