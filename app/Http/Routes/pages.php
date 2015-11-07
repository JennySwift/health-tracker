<?php

Route::get('/colors', function()
{
    return view('pages.colors');
});

//Homepage (entries)
Route::get('/', 'PagesController@entries');

//Menu
Route::get('/foods', 'PagesController@foods');
Route::get('/recipes', 'PagesController@recipes');
Route::get('/food-units', 'PagesController@foodUnits');

//Exercises
Route::get('exercises', 'PagesController@exercises');
Route::get('series', 'PagesController@series');
Route::get('workouts', 'PagesController@workouts');
Route::get('exercise-tags', 'PagesController@exerciseTags');
Route::get('/exercise-units', 'PagesController@exerciseUnits');

//Journal
Route::get('/journal', 'PagesController@journal');

//Jasmine
Route::get('tests', 'PagesController@jasmine');