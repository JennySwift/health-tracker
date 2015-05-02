<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Journal\Journal;
use Faker\Factory as Faker;

class JournalSeeder extends Seeder {

	public function run()
	{
		Journal::truncate();

		/**
		 * create entries for the last 50 days
		 */
		
		$faker = Faker::create();

		foreach (range(1, 5) as $index) {
			Journal::create([
				'date' => '2015-05-02',
				'text' => $faker->paragraphs,
				'user_id' => 1
			]);
		}
	}

}