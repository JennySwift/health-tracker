<?php

Route::resource('workouts', 'Exercises\WorkoutsController', ['only' => ['store']]);
