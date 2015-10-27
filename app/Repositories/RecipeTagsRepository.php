<?php

namespace App\Repositories;

use App\Models\Tags\Tag;

/**
 * Class RecipeTagsRepository
 * @package App\Repositories
 */
class RecipeTagsRepository
{

    /**
     *
     * @return mixed
     */
    public function getRecipeTags()
    {
        return Tag::forCurrentUser()
            ->where('for', 'recipe')
            ->orderBy('name', 'asc')
            ->get();
    }
}