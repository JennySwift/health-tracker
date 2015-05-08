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
		 * It is creating 50 entries but skipping days.
		 *
		 * I have commented out this seeder in DatabaseSeeder.php (line 64) because it was erroring
		 */
		
		$faker = Faker::create();
		$today = Carbon::today();

		foreach (range(0, 49) as $index) {
			$date = $today->subDays($index)->format('Y-m-d');

			Journal::create([
				'date' => $date,
				'text' => $faker->paragraph(),
				'user_id' => 1
			]);		
		}
	}

}