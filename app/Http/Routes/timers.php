<?php

Route::resource('timers', 'Timers\TimersController', ['only' => ['index', 'store']]);
