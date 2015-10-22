<?php

//Resource
Route::resource('foods', 'Menu\FoodsController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);