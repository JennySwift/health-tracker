<?php

Route::resource('exercises', 'Exercises\ExercisesController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
Route::resource('workouts', 'Exercises\WorkoutsController', ['only' => ['store']]);
Route::resource('exerciseTags', 'Exercises\ExerciseTagsController', ['only' => ['index', 'store', 'destroy']]);
Route::resource('exerciseSeries', 'Exercises\ExerciseSeriesController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
Route::resource('exercisePrograms', 'Exercises\ExerciseProgramsController', ['except' => ['create', 'edit']]);

/**
 * @VP:
 * Should I use a nested resource controller here?
 * I tried doing series.entries, but that made the route like this:
 * api/series/{series}/entries/{entries},
 * and I instead want it like this:
 * api/series/{series}/allEntriesForTheSeries :)
 */

Route::resource('seriesEntries', 'Exercises\SeriesEntriesController', ['only' => ['show']]);

/**
 * Entries
 */
//this one is more complicated
Route::get('exerciseEntries/specificExerciseAndDateAndUnit', 'Exercises\ExerciseEntriesController@getEntriesForSpecificExerciseAndDateAndUnit');

//Index method, passing the date as a parameter
Route::get('exerciseEntries/{date}', ['as' => 'api.exerciseEntries.index', 'uses' => 'Exercises\ExerciseEntriesController@index']);

Route::resource('exerciseEntries', 'Exercises\ExerciseEntriesController', ['only' => ['store', 'update', 'destroy']]);