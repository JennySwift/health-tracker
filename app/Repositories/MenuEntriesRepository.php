<?php

namespace App\Repositories;

use App\Http\Transformers\Menu\MenuEntryTransformer;
use App\Models\Menu\Entry;
use Auth;

/**
 * Class MenuEntriesRepository
 * @package App\Repositories
 */
class MenuEntriesRepository
{

    /**
     * Get a user's menu (food/recipe) entries for one day
     * @param $date
     * @return array
     */
    public function getEntriesForTheDay($date)
    {
        $entries = Entry::forCurrentUser()
            ->where('date', $date)
            ->get();

        return transform(createCollection($entries, new MenuEntryTransformer))['data'];
    }
}