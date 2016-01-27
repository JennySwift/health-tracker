<?php

//Index method, passing the date as a parameter
Route::get('menuEntries/{date}', ['as' => 'api.menuEntries.index', 'uses' => 'Menu\MenuEntriesController@index']);

Route::resource('menuEntries', 'Menu\MenuEntriesController', ['only' => ['store', 'destroy']]);