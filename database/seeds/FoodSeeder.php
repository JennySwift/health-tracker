<?php


use App\Models\Menu\Food;
use App\Models\Units\Unit;
use App\User;

/**
 * Class FoodSeeder
 */
class FoodSeeder extends MasterSeeder
{
    /**
     * @var
     */
    private $user;
    private $unitIds;

    /**
     *
     */
    public function run()
    {
        Food::truncate();
        DB::table('food_unit')->truncate();

        foreach(User::all() as $user) {
            $this->user = $user;
            $this->unitIds = Unit::where('user_id', $user->id)
                ->where('for', 'food')
                ->lists('id')
                ->all();

            $this->insertFoods();
        }
    }

    /**
     *
     */
    private function insertFoods()
    {
        foreach (Config::get('foods.userOne') as $tempFood) {
            $food = new Food([
                'name' => $tempFood['name']
            ]);

            $food->user()->associate($this->user);
            $food->save();

            $this->attachUnits($food, $tempFood);
            $this->attachDefaultUnit($food, $tempFood);

            $food->save();
        }
    }

    /**
     *
     * @param Food $food
     * @param $tempFood
     */
    private function attachUnits(Food $food, $tempFood)
    {
        foreach ($tempFood['units'] as $unitName) {
            $unitId = Unit::where('user_id', $this->user->id)
                ->where('name', $unitName)
                ->first();

            $food->units()->attach($unitId, ['calories' => 5]);
        }
    }

    /**
     *
     * @param Food $food
     * @param $tempFood
     */
    private function attachDefaultUnit(Food $food, $tempFood)
    {
        $defaultUnit = Unit::where('user_id', $this->user->id)
            ->where('name', $tempFood['defaultUnit'])
            ->first();

        $food->defaultUnit()->associate($defaultUnit);
    }

}