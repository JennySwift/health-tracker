<?php

//Select
Route::post('select/filterRecipes', 'Menu\RecipesController@filterRecipes');
Route::post('select/recipeContents', 'Menu\RecipesController@getRecipeContents');

//Insert
Route::post('insert/quickRecipe', 'Menu\QuickRecipesController@quickRecipe');

Route::resource('recipes', 'Menu\RecipesController', ['only' => ['store', 'destroy']]);
