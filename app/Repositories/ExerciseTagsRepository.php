<?php

namespace App\Repositories;

use App\Http\Transformers\TagTransformer;
use App\Models\Tags\Tag;

/**
 * Class ExerciseTagsRepository
 * @package App\Repositories
 */
class ExerciseTagsRepository
{

    /**
     *
     * @return mixed
     */
    public function getExerciseTags()
    {
        $tags = Tag::forCurrentUser()
            ->where('for', 'exercise')
            ->orderBy('name', 'asc')
            ->get();

        return transform(createCollection($tags, new TagTransformer))['data'];
    }
}