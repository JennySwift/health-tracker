<?php

namespace App\Repositories;

use App\Models\Menu\Food;

/**
 * Class FoodsRepository
 * @package App\Repositories
 */
class FoodsRepository
{

    /**
     * Get all foods, along with their default unit, default calories,
     * and all their units.
     * Also, add the calories for each food's units. Todo?
     * @return mixed
     */
    public function getAllFoodsWithUnits()
    {
        $foods = Food::forCurrentUser()
            ->with('defaultUnit')
            ->with('units')
            ->orderBy('foods.name', 'asc')->get();

        return $foods;
    }

}