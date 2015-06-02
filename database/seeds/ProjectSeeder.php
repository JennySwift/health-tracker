<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;
use Carbon\Carbon;

use App\Models\Projects\Timer;
use App\Models\Projects\Project;
use App\User;

class ProjectSeeder extends Seeder {

	public function run()
	{
		Project::truncate();
		Timer::truncate();

		$faker = Faker::create();

		//John is payee
		//Jenny is payer
		
		$john_id = User::where('name', 'John')->pluck('id');
		$jenny_id = User::where('name', 'Jenny')->pluck('id');
		$jane_id = User::where('name', 'Jane')->pluck('id');

		$project = Project::create([
			'payee_id' => $john_id,
			'payer_id' => $jenny_id,
			'description' => $faker->word,
			'rate_per_hour' => 40,
			'paid' => 0
		]);

		foreach (range(0, 1) as $index) {
			$finish = $faker->dateTimeBetween($startDate = '-1 days', $endDate = 'now');
			$start = $faker->dateTimeBetween($startDate = '-1 days', $endDate = $finish);

			$timer = new Timer([
				'start' => $start,
				'finish' => $finish
			]);

			$project->timers()->save($timer);
		}

		Project::create([
			'payee_id' => $john_id,
			'payer_id' => $jenny_id,
			'description' => $faker->word,
			'rate_per_hour' => 40,
			'paid' => 0
		]);

		Project::create([
			'payee_id' => $john_id,
			'payer_id' => $jenny_id,
			'description' => $faker->word,
			'rate_per_hour' => 40,
			'paid' => 1
		]);

		//Give the payee another payer

		$project = Project::create([
			'payee_id' => $john_id,
			'payer_id' => $jane_id,
			'description' => $faker->word,
			'rate_per_hour' => 40,
			'paid' => 0
		]);

		//user is payer

		Project::create([
			'payee_id' => $jenny_id,
			'payer_id' => $john_id,
			'description' => $faker->word,
			'rate_per_hour' => 1,
			'paid' => 1
		]);

		Project::create([
			'payee_id' => 2,
			'payer_id' => 1,
			'description' => $faker->word,
			'rate_per_hour' => 2,
			'paid' => 0
		]);
	}

}