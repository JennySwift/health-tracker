<?php

namespace App\Http\Transformers;

use App\Models\Tags\Tag;
use League\Fractal\TransformerAbstract;

/**
 * Class TagTransformer
 */
class TagTransformer extends TransformerAbstract
{
    /**
     * @param Tag $tag
     * @return array
     */
    public function transform(Tag $tag)
    {
        $array = [
            'id' => $tag->id,
            'name' => $tag->name,
        ];

        return $array;
    }

}