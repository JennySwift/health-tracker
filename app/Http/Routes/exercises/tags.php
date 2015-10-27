<?php

Route::resource('exerciseTags', 'Exercises\ExerciseTagsController', ['only' => ['index', 'store', 'destroy']]);
