<?php

namespace App\Repositories;

use App\Http\Transformers\Menu\FoodTransformer;
use App\Models\Menu\Food;
use Auth;

/**
 * Class FoodsRepository
 * @package App\Repositories
 */
class FoodsRepository
{
    /**
     * @var UnitsRepository
     */
    private $unitsRepository;

    /**
     * @param UnitsRepository $unitsRepository
     */
    public function __construct(UnitsRepository $unitsRepository)
    {
        $this->unitsRepository = $unitsRepository;
    }



    /**
     * Get all foods, along with their default unit, default calories,
     * and all their units.
     * Also, add the calories for each food's units. Todo?
     * @return mixed
     */
    public function getFoods()
    {
        $foods = Food::forCurrentUser()
            ->with('defaultUnit')
//            ->with('units')
            ->orderBy('foods.name', 'asc')->get();

        $foods = transform(createCollection($foods, new FoodTransformer));
        return $foods['data'];
    }

}