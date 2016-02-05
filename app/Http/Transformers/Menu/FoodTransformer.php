<?php namespace App\Http\Transformers\Menu;

use App\Http\Transformers\UnitTransformer;
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
     * @var array
     */
    protected $availableIncludes = ['units'];

    /**
     * @var array
     */
    protected $defaultIncludes = ['defaultUnit'];

    /**
    *
    * @param Food $food
    * @return \League\Fractal\Resource\Item
    */
    public function includeDefaultUnit(Food $food)
    {
        if ($food->default_unit_id) {
            return $this->item($food->defaultUnit, new UnitTransformer);
        }
    }

    /**
     *
     * @param Food $food
     * @return \League\Fractal\Resource\Collection
     */
    public function includeUnits(Food $food)
    {
        return $this->collection($food->units, new UnitTransformer);
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

//        if (isset($this->params['units'])) {
//            $array['units'] = $food->units;
//        }

//        if ($food->default_unit_id) {
//            $array['defaultUnit'] = [
//                'id' => $food->defaultUnit->id,
//                'name' => $food->defaultUnit->name
//            ];
//            $array['defaultUnit'] = $food->defaultUnit;
//            $array['defaultUnit'] = $this->includeDefaultUnit($food);
//        }

        return $array;
    }

}