<?php

//this one is more complicated
Route::post('select/specificExerciseEntries', 'Exercises\ExerciseEntriesController@getSpecificExerciseEntries');

//Index method, passing the date as a parameter
Route::get('exerciseEntries/{date}', ['as' => 'api.exerciseEntries.index', 'uses' => 'Exercises\ExerciseEntriesController@index']);

Route::resource('exerciseEntries', 'Exercises\ExerciseEntriesController', ['only' => ['store', 'destroy']]);
