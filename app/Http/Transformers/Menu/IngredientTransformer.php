<?php

namespace App\Http\Transformers\Menu;

use App\Http\Transformers\UnitTransformer;
use App\Models\Menu\Food;
use App\Models\Units\Unit;
use League\Fractal\TransformerAbstract;

/**
 * Class IngredientTransformer
 */
class IngredientTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['food', 'unit'];

    /**
    *
    * @param $ingredient
    * @return \League\Fractal\Resource\Collection
    */
    public function includeFood($ingredient)
    {
        /**
         * @VP:
         * Including the units here isn't working.
         */
        $food = $this->item(Food::find($ingredient->food_id), new FoodTransformer(['units' => true]));
        return $food;
    }

    /**
     *
     * @param $ingredient
     * @return \League\Fractal\Resource\Collection
     */
    public function includeUnit($ingredient)
    {
        return $this->item(Unit::find($ingredient->unit_id), new UnitTransformer);
    }

    /**
     * @param $ingredient
     * @return array
     */
    public function transform($ingredient)
    {
        $array = [
            'quantity' => $ingredient->quantity,
            'description' => $ingredient->description,
        ];

        return $array;
    }

}