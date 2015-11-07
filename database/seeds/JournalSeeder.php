<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Journal\Journal;
use Faker\Factory as Faker;
use Carbon\Carbon;

class JournalSeeder extends Seeder {

	public function run()
	{
		Journal::truncate();
        $faker = Faker::create();

        $users = User::all();
        foreach($users as $user) {
            /**
             * Create entries for the last 50 days.
             */
            foreach (range(0, 49) as $index) {
                $today = Carbon::today();

                $text = $faker->word . '<div>' . $faker->sentence . '</div><div>'. $faker->sentence . '</div>';

                Journal::create([
                    'date' => $today->subDays($index)->format('Y-m-d'),
                    'text' => $text,
                    'user_id' => $user->id
                ]);
            }
        }

	}

}