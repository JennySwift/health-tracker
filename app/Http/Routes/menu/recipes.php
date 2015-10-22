<?php

//Select
Route::post('select/filterRecipes', 'Recipes\RecipesController@filterRecipes');
Route::post('select/recipeContents', 'Recipes\RecipesController@getRecipeContents');

//Insert
Route::post('insert/quickRecipe', 'Recipes\QuickRecipesController@quickRecipe');

Route::resource('recipes', 'Recipes\RecipesController', ['only' => ['store', 'destroy']]);
