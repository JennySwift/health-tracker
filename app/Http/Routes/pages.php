<?php

//Homepage (entries)
Route::get('/', 'PagesController@entries');

//Menu
Route::get('/foods', 'PagesController@foods');
Route::get('/food-units', 'PagesController@foodUnits');

Route::get('/timers/graphs', 'PagesController@timerGraphs');

//Jasmine
Route::get('tests', 'PagesController@jasmine');