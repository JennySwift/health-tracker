<?php

Route::post('select/filterRecipes', 'Menu\RecipesController@filterRecipes');

Route::resource('recipes', 'Menu\RecipesController', ['only' => ['index', 'store', 'show', 'destroy']]);
Route::resource('quickRecipes', 'Menu\QuickRecipesController', ['only' => ['store']]);
