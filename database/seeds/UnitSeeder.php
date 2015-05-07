<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Units\Unit;

class UnitSeeder extends Seeder {

	public function run()
	{
		/**
		 * exercise units
		 */

		Unit::truncate();
		
		Unit::create([
			'name' => 'reps',
			'for' => 'exercise',
			'user_id' => 1
		]);

		Unit::create([
			'name' => 'minutes',
			'for' => 'exercise',
			'user_id' => 1
		]);

		/**
		 * food units
		 */

		Unit::create([
			'name' => 'small',
			'for' => 'food',
			'user_id' => 1
		]);

		Unit::create([
			'name' => 'medium',
			'for' => 'food',
			'user_id' => 1
		]);

		Unit::create([
			'name' => 'large',
			'for' => 'food',
			'user_id' => 1
		]);

		Unit::create([
			'name' => 'grams',
			'for' => 'food',
			'user_id' => 1
		]);
	}

}