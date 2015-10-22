<?php

// Important application routes
require app_path('Http/Routes/auth.php');
require app_path('Http/Routes/pages.php');

// API
Route::group(['namespace' => 'API', 'prefix' => 'api'], function () {
    require app_path('Http/Routes/exercises/entries.php');
    require app_path('Http/Routes/exercises/exercises.php');
    require app_path('Http/Routes/exercises/series.php');
    require app_path('Http/Routes/exercises/workouts.php');

    require app_path('Http/Routes/menu/foods.php');
    require app_path('Http/Routes/menu/recipes.php');

    require app_path('Http/Routes/tags.php');
    require app_path('Http/Routes/units.php');

    require app_path('Http/Routes/autocomplete.php');
    require app_path('Http/Routes/journal.php');
    require app_path('Http/Routes/weights.php');
});

require app_path('Http/Routes/tests.php');

