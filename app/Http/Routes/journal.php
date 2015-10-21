<?php

Route::post('select/filterJournalEntries', 'Journal\JournalController@filter');

Route::resource('journal', 'Journal\JournalController', ['only' => ['show', 'store', 'update']]);