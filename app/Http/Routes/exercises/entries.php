<?php

Route::post('insert/exerciseSet', 'Exercises\ExerciseEntriesController@insertExerciseSet');

Route::post('delete/exerciseEntry', 'Exercises\ExerciseEntriesController@deleteExerciseEntry');

//this one is more complicated
Route::post('select/specificExerciseEntries', 'Exercises\ExerciseEntriesController@getSpecificExerciseEntries');

Route::resource('exerciseEntries', 'Exercises\ExerciseEntriesController', ['only' => ['store']]);
