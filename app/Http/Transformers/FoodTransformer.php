<?php namespace App\Http\Transformers;

use App\Models\Menu\Food;
use League\Fractal\TransformerAbstract;

/**
 * Class FoodTransformer
 */
class FoodTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    private $params;

    /**
     * FoodTransformer constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->params = $params;
    }

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
        ];

        if (isset($this->params['units'])) {
            $array['units'] = $food->units;
        }

        if ($food->default_unit_id) {
            $array['defaultUnit'] = [
                'id' => $food->defaultUnit->id,
                'name' => $food->defaultUnit->name
            ];
        }
        return $array;
    }

}