<?php

Route::resource('exercises', 'Exercises\ExercisesController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);