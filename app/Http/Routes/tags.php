<?php

Route::post('insert/tagsIntoRecipe', 'Recipes\RecipesController@insertTagsIntoRecipe');
Route::post('insert/recipeTag', 'Tags\TagsController@insertRecipeTag');
Route::post('insert/tagInExercise', 'Tags\TagsController@insertTagInExercise');
Route::post('insert/exerciseTag', 'Tags\TagsController@insertExerciseTag');
Route::post('delete/exerciseTag', 'Tags\TagsController@deleteExerciseTag');
Route::post('delete/recipeTag', 'Tags\TagsController@deleteRecipeTag');
Route::post('delete/tagFromExercise', 'Tags\TagsController@deleteTagFromExercise');