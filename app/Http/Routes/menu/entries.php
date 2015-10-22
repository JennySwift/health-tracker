<?php

Route::resource('exerciseEntries', 'Menu\MenuEntriesController', ['only' => ['store']]);