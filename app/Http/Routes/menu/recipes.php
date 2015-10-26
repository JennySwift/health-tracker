<?php

Route::post('select/filterRecipes', 'Menu\RecipesController@filterRecipes');
Route::post('insert/quickRecipe', 'Menu\QuickRecipesController@quickRecipe');

Route::resource('recipes', 'Menu\RecipesController', ['only' => ['index', 'store', 'show', 'destroy']]);
