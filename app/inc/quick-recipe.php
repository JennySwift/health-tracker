<?php

function insertQuickRecipe ($recipe_name, $contents, $steps, $check_similar_names) {
	$similar_names = array();

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

		if ($check_similar_names) {
			$found = checkSimilarFoods($food_name);

			if ($found) {
				$similar_names['foods'][] = array(
					'specified_food' => $food_name,
					'existing_food' => $found
				);
			}

			$found = checkSimilarUnits($unit_name);

			if ($found) {
				$similar_names['units'][] = array(
					'specified_unit' => $unit_name,
					'existing_unit' => $found
				);
			}
		}
		elseif (!$check_similar_names || count($similar_names) === 0) {
			//we can insert things now that no similar names were found, or we have already checked for similar names previously.
		
			//retrieve the id if the food exists, insert and retrieve the id if the food does not exist
			$food_id = insertFoodIfNotExists($food_name);
			$unit_id = insertUnitIfNotExists($unit_name);

			//create the unit if it does not exist. get its id if it does exist.
			quickRecipeCheckUnit($unit_name);





			//insert recipe into recipes table
			$recipe_id = insertQuickRecipeRecipe($recipe_name);

			//insert the method for the recipe
			insertQuickRecipeMethod($recipe_id, $steps);

			//insert the item into food_recipe table
			$data = array(
				'recipe_id' => $recipe_id,
				'food_id' => $food_id,
				'unit_id' => $unit_id,
				'quantity' => $quantity,
				'description' => $description
			);

			insertFoodIntoRecipe($data);
		}
	}

	if (count($similar_names) > 0) {
		return $similar_names;
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

function checkSimilarFoods ($food_name) {
	$count = countItem('foods', $food_name);

	if ($count < 1) {
		//the food does not exist.check if a similar name exists.

		//check if it's singular form exists
		if (substr($food_name, -1) === 's') {
			//the food ends in s. check if it's singular form is in the database
			$similar = substr($food_name, 0, -1);
			$found = DB::table('foods')
				->where('name', $similar)
				->where('user_id', Auth::user()->id)
				->first();
		}
	}
	if (isset($found)) {
		return $found;
	}
}

function checkSimilarUnits ($unit_name) {
	$count = countItem('food_units', $unit_name);

	if ($count < 1) {
		//the unit does not exist.check if a similar name exists.

		//check if it's singular form exists
		if (substr($unit_name, -1) === 's') {
			//the unit ends in s. check if it's singular form is in the database
			$similar = substr($unit_name, 0, -1);
			$found = DB::table('food_units')
				->where('name', $similar)
				->where('user_id', Auth::user()->id)
				->first();
		}
	}
	if (isset($found)) {
		return $found;
	}
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