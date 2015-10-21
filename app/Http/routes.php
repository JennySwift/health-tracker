<?php

// Important application routes
require app_path('Http/Routes/auth.php');
require app_path('Http/Routes/pages.php');

// API
require app_path('Http/Routes/exercises.php');
require app_path('Http/Routes/foods.php');
require app_path('Http/Routes/recipes.php');
require app_path('Http/Routes/tags.php');
require app_path('Http/Routes/autocomplete.php');
require app_path('Http/Routes/entries.php');
require app_path('Http/Routes/journal.php');
require app_path('Http/Routes/weights.php');

//Route::group(['namespace' => 'API', 'prefix' => 'api'], function () {
//    require app_path('Http/Routes/accounts.php');
//    require app_path('Http/Routes/budgets.php');
//});

