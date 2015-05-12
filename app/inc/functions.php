<?php

/**
 * not sure where these functions should go because they are not specific to a model
 */

function pluckName($name, $table)
{
	//for quick recipe
	$name = DB::table($table)
		->where('name', $name)
		->where('user_id', Auth::user()->id)
		->pluck('name');

	return $name;
}

function getId($table, $name)
{
	$id = DB::table($table)
		->where('name', $name)
		->where('user_id', Auth::user()->id)
		->pluck('id');

	return $id;
}

function countItem($table, $name)
{
	$count = DB::table($table)
		->where('name', $name)
		->where('user_id', Auth::user()->id)
		->count();

	return $count;
}

function convertDate($date, $for)
{
	$date = new DateTime($date);

	if ($for === 'user') {
		$date = $date->format('d/m/y');
	}
	elseif ($for === 'sql') {
		$date = $date->format('Y-m-d');
	}
	return $date;
}

function getHowManyDaysAgo($date)
{
	//to find out how many days ago a date was
	$now = new DateTime('now');
	$date = new DateTime($date);
	$diff = $now->diff($date);
	$days_ago = $diff->days;
	return $days_ago;
}

?>