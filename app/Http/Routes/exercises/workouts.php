<?php

Route::post('insert/deleteAndInsertSeriesIntoWorkouts',
    'Exercises\ExerciseSeriesController@deleteAndInsertSeriesIntoWorkouts');

Route::resource('workouts', 'Exercises\WorkoutsController', ['only' => ['store']]);
