<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Units\Unit;
//done
class UnitSeeder extends Seeder {

	public function run()
	{
		/**
		 * exercise units
		 */

		Unit::truncate();
		
		Unit::create([
			'name' => 'reps',
			'user_id' => 1
		]);

		Unit::create([
			'name' => 'minutes',
			'user_id' => 1
		]);

		/**
		 * food units
		 */

		Unit::create([
			'name' => 'small',
			'user_id' => 1
		]);

		Unit::create([
			'name' => 'medium',
			'user_id' => 1
		]);

		Unit::create([
			'name' => 'large',
			'user_id' => 1
		]);

		Unit::create([
			'name' => 'grams',
			'user_id' => 1
		]);
	}

}