<?php

//Select
Route::post('select/filterRecipes', 'Recipes\RecipesController@filterRecipes');
Route::post('select/recipeContents', 'Recipes\RecipesController@getRecipeContents');

//Insert
Route::post('insert/quickRecipe', 'Recipes\QuickRecipesController@quickRecipe');

//Insert or update? It is a separate table.
Route::post('insert/recipeMethod', 'Recipes\RecipesController@insertRecipeMethod');

//Update
Route::post('insert/foodIntoRecipe', 'Recipes\RecipesController@insertFoodIntoRecipe');
Route::post('delete/foodFromRecipe', 'Recipes\RecipesController@deleteFoodFromRecipe');
Route::post('update/recipeMethod', 'Recipes\RecipesController@updateRecipeMethod');
Route::post('insert/tagsIntoRecipe', 'Recipes\RecipesController@insertTagsIntoRecipe');

Route::resource('recipes', 'Recipes\RecipesController', ['only' => ['store', 'destroy']]);
