<?php namespace App\Http\Transformers;

use App\Models\Menu\Food;
use League\Fractal\TransformerAbstract;

/**
 * Class FoodTransformer
 */
class FoodTransformer extends TransformerAbstract
{
    /**
     * Transform food response
     * @param Food $food
     * @return array
     */
    public function transform(Food $food)
    {
        $array = [
            'id' => $food->id,
            'name' => $food->name,
            'path' => $food->path,
            'defaultCalories' => $food->getDefaultCalories(),
//            'units' => $food->units
        ];

        if ($food->default_unit_id) {
            $array['defaultUnit'] = [
                'id' => $food->defaultUnit->id,
                'name' => $food->defaultUnit->name
            ];
        }
        return $array;
    }

}