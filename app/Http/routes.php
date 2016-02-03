<?php

//Auth
require app_path('Http/Routes/auth.php');
//Test routes
require app_path('Http/Routes/tests.php');
//Jasmine
Route::get('tests', 'PagesController@jasmine');
//Home page (entries)
Route::get('/', 'PagesController@entries');

// API
Route::group(['namespace' => 'API', 'prefix' => 'api'], function () {
    require app_path('Http/Routes/exercises.php');
    require app_path('Http/Routes/menu.php');
    require app_path('Http/Routes/timers.php');

    //Calories
    Route::resource('calories', 'Calories\CaloriesController', ['only' => 'show']);

    //Weights
    Route::post('insert/weight', 'Weights\WeightsController@insertOrUpdateWeight');
    Route::resource('weights', 'Weights\WeightsController', ['only' => 'show']);

    //Units
    Route::resource('foodUnits', 'Menu\FoodUnitsController', ['only' => ['index', 'store', 'update', 'destroy']]);
    Route::resource('exerciseUnits', 'Exercises\ExerciseUnitsController', ['only' => ['index', 'store', 'update', 'destroy']]);

    //Journal
    Route::resource('journal', 'Journal\JournalController', ['only' => ['index', 'show', 'store', 'update']]);

    //Autocomplete-selects rows from both foods and recipes table
    Route::post('autocomplete/menu', 'Search\AutocompleteController@autocompleteMenu');
});


