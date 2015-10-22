<?php

use App\Models\Menu\Food;

Route::get('/test', function()
{
    $food = Food::first();
    //dd($food);
    return $food;
});