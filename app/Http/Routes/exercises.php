<?php

Route::post('insert/exerciseSet', 'Exercises\ExerciseEntriesController@insertExerciseSet');
Route::post('delete/exerciseEntry', 'Exercises\ExerciseEntriesController@deleteExerciseEntry');
//this one is more complicated
Route::post('select/specificExerciseEntries', 'Exercises\ExerciseEntriesController@getSpecificExerciseEntries');
//this should be ExerciseSeriesHistoryController, index (if returns multiple series, show if returns one series) method
Route::post('select/exerciseSeriesHistory', 'Exercises\ExercisesController@getExerciseSeriesHistory');
Route::post('insert/deleteAndInsertSeriesIntoWorkouts',
    'Exercises\ExerciseSeriesController@deleteAndInsertSeriesIntoWorkouts');
Route::post('insert/tagsInExercise', 'Tags\TagsController@insertTagsInExercise');
Route::post('insert/exerciseUnit', 'Units\UnitsController@insertExerciseUnit');
Route::post('delete/exerciseUnit', 'Units\UnitsController@deleteExerciseUnit');

Route::resource('exercises', 'Exercises\ExercisesController', ['except' => ['index']]);
Route::resource('workouts', 'Exercises\WorkoutsController', ['only' => ['store']]);
Route::resource('exerciseEntries', 'Exercises\ExerciseEntriesController', ['only' => ['store']]);
Route::resource('exerciseSeries', 'Exercises\ExerciseSeriesController', ['only' => ['store', 'show', 'destroy']]);