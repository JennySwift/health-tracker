<?php

Route::post('filterJournalEntries', 'Journal\JournalController@filter');

Route::resource('journal', 'Journal\JournalController', ['only' => ['show', 'store', 'update']]);