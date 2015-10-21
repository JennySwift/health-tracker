<?php

//this should be ExerciseSeriesHistoryController, index (if returns multiple series, show if returns one series) method
Route::post('select/exerciseSeriesHistory', 'Exercises\ExercisesController@getExerciseSeriesHistory');

Route::resource('exerciseSeries', 'Exercises\ExerciseSeriesController', ['only' => ['store', 'show', 'destroy']]);