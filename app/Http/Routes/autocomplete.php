<?php

//This selects rows from both foods and recipes table.
Route::post('autocomplete/menu', 'Search\AutocompleteController@autocompleteMenu');
Route::post('autocomplete/exercise', 'Search\AutocompleteController@autocompleteExercise');
Route::post('autocomplete/food', 'Search\AutocompleteController@autocompleteFood');