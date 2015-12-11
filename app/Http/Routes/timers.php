<?php

Route::get('timers/checkForTimerInProgress', 'Timers\TimersController@checkForTimerInProgress');
Route::get('activities/getTotalMinutesForDay', 'Timers\ActivitiesController@calculateTotalMinutesForDay');
Route::resource('timers', 'Timers\TimersController', ['only' => ['index', 'store', 'update', 'destroy']]);
Route::resource('activities', 'Timers\ActivitiesController', ['only' => ['index', 'store', 'update', 'destroy']]);
