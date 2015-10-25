<?php

Route::resource('exerciseSeries', 'Exercises\ExerciseSeriesController', ['only' => ['store', 'show', 'destroy']]);