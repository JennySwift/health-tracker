<?php

namespace App\Repositories;

use App\Models\Menu\Food;
use App\Models\Units\Unit;

/**
 * Class QuickRecipesRepository
 * @package App\Repositories
 */
class QuickRecipesRepository {
    /**
     * @var UnitsRepository
     */
    private $unitsRepository;
    /**
     * @var FoodsRepository
     */
    private $foodsRepository;
    /**
     * @var RecipesRepository
     */
    private $recipesRepository;

    /**
     * @param UnitsRepository $unitsRepository
     * @param FoodsRepository $foodsRepository
     * @param RecipesRepository $recipesRepository
     */
    public function __construct(UnitsRepository $unitsRepository, FoodsRepository $foodsRepository, RecipesRepository $recipesRepository)
    {
        $this->unitsRepository = $unitsRepository;
        $this->foodsRepository = $foodsRepository;
        $this->recipesRepository = $recipesRepository;
    }

    /**
     *
     * @param $contents
     * @return array
     */
    public function checkEntireRecipeForSimilarNames($contents)
    {
        //$index is so if a similar name is found,
        //I know what index it is in the quick recipe array for the javascript.
        $index = -1;
        $similarNames = [];

        foreach ($contents as $item) {
            $index++;

            $similarFoodInfo = $this->populateSimilarFoodNames($item['food'], $index);

            if (count($similarFoodInfo) > 0) {
                $similarNames['foods'][] = $similarFoodInfo;
            }

            $similarUnitInfo = $this->populateSimilarUnitNames($item['unit'], $index);

            if (count($similarUnitInfo) > 0) {
                $similarNames['units'][] = $similarUnitInfo;
            }
        }

        return $similarNames;
    }

    /**
     *
     * @param $unitName
     * @param $index
     * @return array
     */
    public function populateSimilarUnitNames($unitName, $index)
    {
        $foundUnit = $this->checkSimilarNames($unitName, 'units');

        if ($foundUnit) {
            return [
                'specified_unit' => ['name' => $unitName],
                'existing_unit' => ['name' => $foundUnit],
                'checked' => $foundUnit,
                'index' => $index
            ];
        }

        return [];
    }

    /**
     *
     * @param $foodName
     * @param $index
     * @return array
     */
    public function populateSimilarFoodNames($foodName, $index)
    {
        $foundFood = $this->checkSimilarNames($foodName, 'foods');

        if ($foundFood) {
            return [
                'specified_food' => ['name' => $foodName],
                'existing_food' => ['name' => $foundFood],
                'checked' => $foundFood,
                'index' => $index
            ];
        }

        return [];
    }

    /**
     * Currently this checks the units table for similar names.
     * I should change it to find them only if the unit type is for food.
     * @param $name
     * @param $table
     * @return mixed
     */
    public function checkSimilarNames($name, $table)
    {
        $count = countItem($table, $name);

        if ($count < 1) {
            //the name does not exist

            if (substr($name, -3) === 'ies') {
                //the name ends in 'ies'. check if it's singular form exists.
                $similar_name = substr($name, 0, -3) . 'y';
                $found = pluckName($similar_name, $table);
            }
            elseif (substr($name, -1) === 'y') {
                //the name ends in 'y'. Check if it's plural form exists.
                $similar_name = substr($name, 0, -1) . 'ies';
                $found = pluckName($similar_name, $table);
            }

            elseif (substr($name, -1) === 's' && !isset($found)) {
                //the name ends in s. check if its singular form is in the database
                $similar_name = substr($name, 0, -1);
                $found = pluckName($similar_name, $table);

                //if nothing was found, check if its plural form is in the database
                if (!isset($found)) {
                    $similar_name = $name . 'es';
                    $found = pluckName($similar_name, $table);
                }
            }

            //check if it's plural form exists if no singular forms were found
            if (!isset($found)) {
                $similar_name = $name . 's';
                $found = pluckName($similar_name, $table);
            }
        }
        if (isset($found)) {
            return $found;
        }
    }


}