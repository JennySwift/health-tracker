<?php

Route::resource('recipeTags', 'Menu\RecipeTagsController', ['only' => ['index', 'store', 'destroy']]);
