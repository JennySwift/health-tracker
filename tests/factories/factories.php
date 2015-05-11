<?php

$factory('App\User', [
	'name' => 'Dummy1',
	'email' => 'cheezyspaghetti@gmail.com',
	'password' => bcrypt('abcdefg')
]);

$factory('App\Models\Foods\Food', [
	'name' => 'apple',
	'user_id' => 1,
	'default_unit_id' => 'factory:App\Models\Units\Unit'
]);

$factory('App\Models\Units\Unit', [
	'name' => 'reps',
	'for' => 'exercise',
	'user_id' => 1
]);