<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;
use App\Models\Timers\Timer;

class TimerSeeder extends Seeder {

	public function run()
	{
		Timer::truncate();

		$faker = Faker::create();

		//user is payee
		
		Timer::create([
			'payee_id' => 1,
			'payer_id' => 2,
			'rate_per_hour' => 40,
			'paid' => 0
		]);

		Timer::create([
			'payee_id' => 1,
			'payer_id' => 2,
			'rate_per_hour' => 40,
			'paid' => 0
		]);

		Timer::create([
			'payee_id' => 1,
			'payer_id' => 2,
			'rate_per_hour' => 40,
			'paid' => 1
		]);

		//user is payer

		Timer::create([
			'payee_id' => 2,
			'payer_id' => 1,
			'rate_per_hour' => 1,
			'paid' => 1
		]);

		Timer::create([
			'payee_id' => 2,
			'payer_id' => 1,
			'rate_per_hour' => 2,
			'paid' => 0
		]);
	}

}