<?php


use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\Food;
use App\Models\Units\Unit;

use Laracasts\TestDummy\Factory as Dummy;
use App\User;

class FoodSeeder extends MasterSeeder {
  
  protected $user_one_foods = [
    'apple', 'banana', 'orange', 'mango', 'watermelon', 'papaya', 'pear', 'peach', 
    'nectarine', 'plum', 'rockmelon', 'blueberry', 'strawberry', 'raspberry', 'blackberry',
    'walnut', 'brazilnut', 'cashew', 'almond', 'sesame seeds', 'pumpkin seeds', 'sunflower seeds'
  ];
	protected $user_two_foods = [
	  'quinoa', 'rice', 'buckwheat'
  ];

	public function run()
	{
		// DB::statement('SET FOREIGN_KEY_CHECKS=0');

		Food::truncate();
		//$faker = Faker::create();

		/**
		 * Objective:
		 * Give one user all foods in $user_one_foods.
		 * Give second user all foods in $user_two_foods.
		 * Add a default_unit_id to most foods, but not all of them, to make it more realistic.
		 * 
		 * The default_unit_id must be a unit id that belongs to the food (in food_units table).
		 * The problem is the food_units table is not yet seeded, so we don't yet know what units belong to a food.
		 * But the food_units table needs the foods table to be seeded before it is seeded.
		 * I got this working by setting the default_unit_id in the FoodUnitSeeder. Could it be better?
		 *
		 * And I don't really see much benefit of using TestDummy yet. How does it help?
		 */
    $this->createUserOne();
    $this->createUserTwo();
		

		// DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}
	
	private function createUserOne(){
	  foreach ($this->user_one_foods as $food) {
			Dummy::create('App\Models\Foods\Food', [
				'name' => $food,
				'user_id' => 1
			]);
		} 
	}
	
	private function createUserTwo(){
	  foreach ($this->user_two_foods as $food) {
			Dummy::create('App\Models\Foods\Food', [
				'name' => $food,
				'user_id' => 2
			]);
		}
	}

}