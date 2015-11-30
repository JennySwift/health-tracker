<?php

use App\Models\Sleep\Sleep;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SleepSeeder extends Seeder {

    private $user;
    private $faker;

    public function run()
	{
		Sleep::truncate();

        $this->faker = Faker::create();

        $users = User::all();

        foreach($users as $user) {
            $this->user = $user;

            /**
             * Create entries for the last 50 days.
             */
            foreach (range(0, 49) as $index) {
                $today = Carbon::today();
                $date = $today->subDays($index);

                $entry = new Sleep([
                    'start' => $date->hour(21)->format('Y-m-d H:i:s'),
                    'finish' => $date->addDays(1)->hour(8)->minute($this->faker->randomElement([0,15,30,45]))->format('Y-m-d H:i:s')
                ]);

                $entry->user()->associate($this->user);
                $entry->save();
            }
        }

	}

}