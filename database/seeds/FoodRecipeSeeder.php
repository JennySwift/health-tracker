<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

use App\Models\Units\Unit;
use App\Models\Foods\Food;

class FoodRecipeSeeder extends Seeder {

	public function run()
	{
		DB::table('food_recipe')->truncate();
		
		$faker = Faker::create();

        $users = User::all();

        foreach($users as $user) {
            $food_ids = Food::where('user_id', $user->id)->lists('id')->all();

            //Insert some rows for recipe_id 1

            foreach (range(0,2) as $number) {
                $unit_ids_owned_by_food = [];
                while (count($unit_ids_owned_by_food) === 0) {
                    $food_id = $faker->randomElement($food_ids);
                    $food = Food::find($food_id);

                    /**
                     * @VP:
                     * Why do I get the following error when I specify 'units.id?'
                     * Integrity constraint violation: 1052 Column 'id' in field list is ambiguous (SQ
                     * L: select `id` from `units` inner join `food_unit` on `units`.`id` = `food_unit`.`unit_id` where
                     * `food_unit`.`food_id` = 1)
                     */
                    // $unit_ids_owned_by_food = $food->units()->lists('units.id');

                    //populate the unit_ids_owned_by_food array
                    $units_owned_by_food = $food->units;
                    foreach ($units_owned_by_food as $unit) {
                        $unit_ids_owned_by_food[] = $unit->id;
                    }
                }

                DB::table('food_recipe')->insert([
                    'recipe_id' => 1,
                    'food_id' => $food_id,
                    'unit_id' => $faker->randomElement($unit_ids_owned_by_food),
                    'quantity' => 3,
                    'description' => '',
                    'user_id' => $user->id
                ]);
            }

            //Insert some rows for recipe_id 2

            foreach (range(0,2) as $number) {
                $unit_ids_owned_by_food = [];
                while (count($unit_ids_owned_by_food) === 0) {
                    $food_id = $faker->randomElement($food_ids);
                    $food = Food::find($food_id);

                    //populate the unit_ids_owned_by_food array
                    $units_owned_by_food = $food->units;

                    foreach ($units_owned_by_food as $unit) {
                        $unit_ids_owned_by_food[] = $unit->id;
                    }
                }

                DB::table('food_recipe')->insert([
                    'recipe_id' => 2,
                    'food_id' => $food_id,
                    'unit_id' => $faker->randomElement($unit_ids_owned_by_food),
                    'quantity' => 3,
                    'description' => '',
                    'user_id' => $user->id
                ]);
            }
        }

	}

}