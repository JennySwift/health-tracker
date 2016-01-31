<?php

Route::post('select/filterRecipes', 'Menu\RecipesController@filterRecipes');
Route::get('quickRecipes/checkForSimilarNames', 'Menu\QuickRecipesController@checkForSimilarNames');

Route::resource('recipes', 'Menu\RecipesController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
Route::resource('quickRecipes', 'Menu\QuickRecipesController', ['only' => ['store']]);
