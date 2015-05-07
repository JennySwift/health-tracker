<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Foods\Food;

class FoodSeeder extends Seeder {

	public function run()
	{
		Food::truncate();
		
		Food::create([
			'name' => 'apple',
			'user_id' => 1
		]);

		Food::create([
			'name' => 'orange',
			'user_id' => 1
		]);

		Food::create([
			'name' => 'banana',
			'user_id' => 1
		]);
	}

}