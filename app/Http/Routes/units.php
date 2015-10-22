<?php

Route::resource('units', 'Units\UnitsController', ['only' => ['store', 'destroy']]);
