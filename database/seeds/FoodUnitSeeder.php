<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\FoodUnit;
//done

class FoodUnitSeeder extends Seeder {

	public function run()
	{
		FoodUnit::truncate();

		FoodUnit::create([
			'name' => 'small',
			'user_id' => 1
		]);

		FoodUnit::create([
			'name' => 'medium',
			'user_id' => 1
		]);

		FoodUnit::create([
			'name' => 'large',
			'user_id' => 1
		]);

		FoodUnit::create([
			'name' => 'grams',
			'user_id' => 1
		]);
	}

}