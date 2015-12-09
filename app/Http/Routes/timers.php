<?php

Route::resource('timers', 'Timers\TimersController', ['only' => ['index', 'store', 'update']]);
Route::resource('activities', 'Timers\ActivitiesController', ['only' => ['index']]);
