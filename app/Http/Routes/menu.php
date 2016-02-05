<?php

Route::resource('foods', 'Menu\FoodsController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
Route::resource('menu', 'Menu\MenuController', ['only' => ['index']]);

//Index method, passing the date as a parameter
Route::get('menuEntries/{date}', ['as' => 'api.menuEntries.index', 'uses' => 'Menu\MenuEntriesController@index']);

Route::resource('menuEntries', 'Menu\MenuEntriesController', ['only' => ['store', 'destroy']]);

Route::resource('recipeTags', 'Menu\RecipeTagsController', ['only' => ['index', 'store', 'destroy']]);

Route::post('select/filterRecipes', 'Menu\RecipesController@filterRecipes');
Route::get('quickRecipes/checkForSimilarNames', 'Menu\QuickRecipesController@checkForSimilarNames');

Route::resource('recipes', 'Menu\RecipesController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
Route::resource('quickRecipes', 'Menu\QuickRecipesController', ['only' => ['store']]);