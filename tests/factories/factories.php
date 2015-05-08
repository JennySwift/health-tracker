<?php

$factory('App\Models\Foods\Food', [
	'name' => 'apple',
	'user_id' => 1,
	'default_unit_id' => $faker->numberBetween($min = 3, $max = 6)
]);