<?php

//Index method, passing what the user has typed into the filter as a parameter
//Route::get('journal/{typing}', ['as' => 'api.journal.index', 'uses' => 'Journal\JournalController@index']);

Route::resource('journal', 'Journal\JournalController', ['only' => ['index', 'show', 'store', 'update']]);