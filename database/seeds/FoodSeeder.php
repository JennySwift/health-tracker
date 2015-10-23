<?php


use App\Models\Menu\Food;
use App\Models\Units\Unit;
use App\User;

/**
 * Class FoodSeeder
 */
class FoodSeeder extends MasterSeeder
{
    private $user;

    /**
     *
     */
    public function run()
    {
        Food::truncate();
        DB::table('food_unit')->truncate();

        foreach(User::all() as $user) {
            $this->user = $user;
            $unit_ids = Unit::where('user_id', $user->id)
                ->where('for', 'food')
                ->limit(2)
                ->lists('id')
                ->all();

            $this->insertFoods($unit_ids);
        }
    }

    private function insertFoods($unit_ids)
    {
        foreach (Config::get('foods.userTwo') as $food) {
            $food = new Food([
                'name' => $food
            ]);

            $food->user()->associate($this->user);
            $food->save();

            //Attach the units
            foreach ($unit_ids as $unit_id) {
                var_dump($unit_id);
                $food->units()->attach($unit_id, ['calories' => 5]);
            }

            //Attach the default unit
            $defaultUnit = Unit::find($unit_ids[0]);
            $food->defaultUnit()->associate($defaultUnit);
            $food->save();

        }
    }



}