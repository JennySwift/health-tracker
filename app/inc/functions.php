<?php

use App\Series_workout;
use App\Food_recipe;

include('quick-recipe-functions.php');
include('recipe-filter.php');

function getRecipeEntries () {
	$recipe_id = $request->get('recipe_id');

	$recipe_info = array(
		"id" => $recipe_id
	);
	
	$contents = Food_recipe::getRecipeContents($recipe_id);
	
	$array = array(
		"recipe_info" => $recipe_info,
		"contents" => $contents
	);
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

function getId ($table, $name) {
	$id = DB::table($table)
		->where('name', $name)
		->where('user_id', Auth::user()->id)
		->pluck('id');

	return $id;
}

function countItem ($table, $name) {
	$count = DB::table($table)
		->where('name', $name)
		->where('user_id', Auth::user()->id)
		->count();

	return $count;
}

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