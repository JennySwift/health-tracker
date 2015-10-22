<?php

Route::resource('foodUnits', 'Menu\FoodUnitsController', ['only' => ['index', 'store', 'update', 'destroy']]);
Route::resource('exerciseUnits', 'Exercises\ExerciseUnitsController', ['only' => ['index', 'store', 'update', 'destroy']]);
