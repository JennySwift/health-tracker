<?php

Route::resource('timers', 'Timers\TimersController', ['only' => ['index', 'store']]);
Route::resource('activities', 'Timers\ActivitiesController', ['only' => ['index']]);
