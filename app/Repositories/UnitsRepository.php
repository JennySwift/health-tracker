<?php

namespace App\Repositories;


use App\Models\Units\Unit;
use DB;
use Auth;

/**
 * Class UnitsRepository
 * @package App\Repositories
 */
class UnitsRepository {

    /**
     *
     * @return mixed
     */
    public function getExerciseUnits()
    {
        return Unit::forCurrentUser()
            ->where('for', 'exercise')
            ->orderBy('name', 'asc')
            ->get();

    }

    /**
     *
     * @return mixed
     */
    public function getFoodUnits()
    {
        return Unit::forCurrentUser()
            ->where('for', 'food')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     *
     * @param $food
     * @return mixed
     */
    public function getFoodUnitsWithCalories($food)
    {
        $units = Unit::forCurrentUser()
            ->where('for', 'food')
            ->orderBy('name', 'asc')
            ->get();

        //Add the calories for the units that belong to the food
        foreach ($units as $unit) {
            $calories = DB::table('food_unit')
                ->where('food_id', $food->id)
                ->where('unit_id', $unit->id)
                ->pluck('calories');

            $unit->calories = $calories;
        }

        return $units;
    }

    /**
     *
     * @param $name
     * @return Unit
     */
    public function insert($name)
    {
        $unit = new Unit([
            'name' => $name
        ]);
        $unit->user()->associate(Auth::user());
        $unit->save();

        return $unit;
    }

}