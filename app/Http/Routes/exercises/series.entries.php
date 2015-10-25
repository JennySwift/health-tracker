<?php

/**
 * @VP:
 * Should I use a nested resource controller here?
 * I tried doing series.entries, but that made the route like this:
 * api/series/{series}/entries/{entries},
 * and I instead want it like this:
 * api/series/{series}/allEntriesForTheSeries :)
 */

Route::resource('seriesEntries', 'Exercises\SeriesEntriesController', ['only' => ['show']]);