<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\Food;
use App\Models\Units\Unit;
use Faker\Factory as Faker;

/**
 * @VP:
 * Why do I get this error if I try to use the following line?
 * [ErrorException]
 * The use statement with non-compound name 'DB' has no effect
 */
  // use DB;
  //Only needed for classes that have a namespace

class FoodUnitSeeder extends MasterSeeder {

	public function run()
	{
		// Model::unguard();
		DB::table('food_unit')->truncate();

		/**
		 * Objective:
		 * Add a random number of rows to the food_units table for each food
		 * so that each food has many units (no duplicates).
		 * Set the calories for some, but not all, of the rows.
		 * My code works, but could it be better?
		 *
		 * Also, for a food's default unit, is it better to have a 'default_unit_id' column in my foods table (with a foreign key),
		 * or to have a 'default' column in my food_units table (with a boolean value)?
		 */
		 $this->createPivotRows();
	}
	
	private function createPivotRows()
	{
		$foods = Food::all();
		
		foreach ($foods as $food) {
			//Add a few units for each food	
			$this->createPivotRow($food);	
		}
	}

	private function createPivotRow($food)
	{
	  foreach (Unit::where('for', 'food')->get() as $unit) {
	    // Decide if this food should have this unit
  		if($this->faker->boolean($chanceOfGettingTrue = 50)) {
  			
  			$data = [
				'food_id' => $food->id,
				'unit_id' => $unit->id,
				'user_id' => 1
  		  ];
  
  			$data['calories'] = $this->hasCalories();
  
  			DB::table('food_unit')->insert($data);	
  		}
	  }
	  $this->setDefaultFoodUnits($food);
	}
	
	private function setDefaultFoodUnits($food)
	{
	    //Now that both the foods table and the food units table have been populated
		//set the default unit ids of foods

		//Grab the units that belong to the food
		$unit_ids_for_food = DB::table('food_unit')->where('food_id', $food->id)->lists('unit_id');
		// dd($unit_ids_for_food);
		//Update the default_unit_id for that food
		$food->update([
			'default_unit_id' => $this->faker->randomElement($unit_ids_for_food)
		]);
		
	}
	
	// Decide if this food with this unit should have calories set
	private function hasCalories()
	{
	    if ($this->faker->boolean($chanceOfGettingTrue = 80)) {
				return $this->faker->numberBetween($min = 30, $max = 150);
			} 
	}

}