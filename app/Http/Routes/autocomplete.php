<?php

//This selects rows from both foods and recipes table.
Route::post('select/autocompleteMenu', 'Search\AutocompleteController@autocompleteMenu');
Route::post('select/autocompleteExercise', 'Search\AutocompleteController@autocompleteExercise');
Route::post('select/autocompleteFood', 'Search\AutocompleteController@autocompleteFood');