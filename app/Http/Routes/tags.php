<?php

Route::resource('tags', 'Tags\TagsController', ['only' => ['destroy']]);
