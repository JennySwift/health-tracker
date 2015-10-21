<?php

Route::post('insert/tagsInExercise', 'Tags\TagsController@insertTagsInExercise');
Route::post('insert/tagInExercise', 'Tags\TagsController@insertTagInExercise');
Route::post('delete/tagFromExercise', 'Tags\TagsController@deleteTagFromExercise');

Route::resource('exercises', 'Exercises\ExercisesController', ['except' => ['index']]);