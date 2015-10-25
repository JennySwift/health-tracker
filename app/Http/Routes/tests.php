<?php

use App\Models\Menu\Entry;
use App\Models\Menu\Food;

Route::get('/test', function()
{
    $entry = Entry::first();
    //dd($entry);
    return $entry->getCalories();
});