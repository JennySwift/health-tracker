<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Unit;
//done
class UnitSeeder extends Seeder {

	public function run()
	{
		ExerciseUnit::truncate();
		
		ExerciseUnit::create([
			'name' => 'reps',
			'user_id' => 1
		]);

		ExerciseUnit::create([
			'name' => 'minutes',
			'user_id' => 1
		]);

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