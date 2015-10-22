<?php

Route::resource('exercises', 'Exercises\ExercisesController', ['except' => ['index']]);