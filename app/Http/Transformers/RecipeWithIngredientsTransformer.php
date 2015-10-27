<?php namespace App\Http\Transformers;

use App\Models\Menu\Recipe;
use League\Fractal\TransformerAbstract;

/**
 * Class RecipeWithIngredientsTransformer
 */
class RecipeWithIngredientsTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['tags'];

    /**
     * @VP:
     * How do I pass a second parameter to this method?
     * I wanted to have a second parameter, $withContents,
     * to determine whether or not the recipe should have extra properties attached.
     * But this sort of thing (lol) didn't work
     * (missing argument 2 for the transform method):
     * return transform(createItem($recipe, new RecipeTransformer, true));
     * So instead I just created a second recipe transformer,
     * which is much the same as this one.
     * @return array
     */
    public function transform(Recipe $recipe)
    {
        $array = [
            'id' => $recipe->id,
            'name' => $recipe->name,
            'steps' => $recipe->steps,
            'tag_ids' => $recipe->tags->lists('id'),
            'ingredients' => $recipe->getIngredients()
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

