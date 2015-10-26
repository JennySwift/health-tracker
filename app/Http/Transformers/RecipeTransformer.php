<?php namespace App\Http\Transformers;

use App\Models\Menu\Recipe;
use League\Fractal\TransformerAbstract;

/**
 * Class RecipeTransformer
 */
class RecipeTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(Recipe $recipe, $withContents = false)
    {
        $array = [
            'id' => $recipe->id,
            'name' => $recipe->name,
            //Todo: I don't need all the tag attributes here
            'tags' => $recipe->tags
        ];

        if ($withContents) {

        }

        return $array;
    }

}

