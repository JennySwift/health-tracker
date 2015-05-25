<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Journal\Journal;
use Faker\Factory as Faker;
use Carbon\Carbon;

class JournalSeeder extends Seeder {

	public function run()
	{
		Journal::truncate();

		/**
		 * Create entries for the last 50 days.
		 */
		
		$faker = Faker::create();

		foreach (range(0, 49) as $index) {
			$today = Carbon::today();

			Journal::create([
				'date' => $today->subDays($index)->format('Y-m-d'),
				'text' => $faker->paragraph(),
				'user_id' => 1
			]);		
		}
	}

}