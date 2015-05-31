<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;
use Carbon\Carbon;

use App\Models\Timers\Timer;
use App\Models\Timers\Time;

class TimerSeeder extends Seeder {

	public function run()
	{
		Timer::truncate();
		Time::truncate();

		$faker = Faker::create();

		//user is payee
		
		$timer = Timer::create([
			'payee_id' => 1,
			'payer_id' => 2,
			'description' => $faker->word,
			'rate_per_hour' => 40,
			'paid' => 0
		]);

		foreach (range(0, 1) as $index) {
			$finish = $faker->dateTimeBetween($startDate = '-1 days', $endDate = 'now');
			$start = $faker->dateTimeBetween($startDate = '-1 days', $endDate = $finish);

			$time = new Time([
				'start' => $start,
				'finish' => $finish
			]);

			$timer->times()->save($time);
		}

		

		Timer::create([
			'payee_id' => 1,
			'payer_id' => 2,
			'description' => $faker->word,
			'rate_per_hour' => 40,
			'paid' => 0
		]);

		Timer::create([
			'payee_id' => 1,
			'payer_id' => 2,
			'description' => $faker->word,
			'rate_per_hour' => 40,
			'paid' => 1
		]);

		//user is payer

		Timer::create([
			'payee_id' => 2,
			'payer_id' => 1,
			'description' => $faker->word,
			'rate_per_hour' => 1,
			'paid' => 1
		]);

		Timer::create([
			'payee_id' => 2,
			'payer_id' => 1,
			'description' => $faker->word,
			'rate_per_hour' => 2,
			'paid' => 0
		]);
	}

}