<?php

Route::resource('exerciseSeries', 'Exercises\ExerciseSeriesController', ['only' => ['index', 'store', 'show', 'destroy']]);