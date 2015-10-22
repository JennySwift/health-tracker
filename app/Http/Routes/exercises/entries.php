<?php

Route::post('insert/exerciseSet', 'Exercises\ExerciseEntriesController@insertExerciseSet');

//this one is more complicated
Route::post('select/specificExerciseEntries', 'Exercises\ExerciseEntriesController@getSpecificExerciseEntries');

/**
 * @VP:
 * How to send the date for the index method?
 */
Route::resource('exerciseEntries', 'Exercises\ExerciseEntriesController', ['only' => ['store', 'destroy']]);
