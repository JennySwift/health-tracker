<?php namespace App\Http\Transformers\Menu;

use App\Http\Transformers\TagTransformer;
use App\Models\Menu\Recipe;
use League\Fractal\TransformerAbstract;

/**
 * Class RecipeTransformer
 */
class RecipeTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['tags'];

    /**
     *
     * @param Recipe $recipe
     * @return array
     */
    public function transform(Recipe $recipe)
    {
        $array = [
            'id' => $recipe->id,
            'name' => $recipe->name
        ];

        return $array;
    }

    /**
     *
     * @param Recipe $recipe
     * @return \League\Fractal\Resource\Collection
     */
    public function includeTags(Recipe $recipe)
    {
        $tags = $recipe->tags;

        return createCollection($tags, new TagTransformer);
    }


}

