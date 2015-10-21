<?php

Route::post('select/filterRecipes', 'Recipes\RecipesController@filterRecipes');
Route::post('select/recipeContents', 'Recipes\RecipesController@getRecipeContents');
Route::post('insert/quickRecipe', 'Recipes\QuickRecipesController@quickRecipe');
Route::post('insert/recipeMethod', 'Recipes\RecipesController@insertRecipeMethod');
Route::post('insert/recipe', 'Recipes\RecipesController@insertRecipe');
Route::post('insert/recipeEntry', 'Recipes\RecipesController@insertRecipeEntry');
Route::post('insert/foodIntoRecipe', 'Recipes\RecipesController@insertFoodIntoRecipe');
Route::post('delete/recipe', 'Recipes\RecipesController@deleteRecipe');
Route::post('delete/foodFromRecipe', 'Recipes\RecipesController@deleteFoodFromRecipe');
Route::post('delete/recipeEntry', 'Recipes\RecipesController@deleteRecipeEntry');
Route::post('update/recipeMethod', 'Recipes\RecipesController@updateRecipeMethod');