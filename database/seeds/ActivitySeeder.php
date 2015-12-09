<?php

use App\Models\Timers\Activity;
use App\Models\Timers\Timer;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ActivitySeeder extends Seeder {

    private $user;
    private $faker;
    private $date;

    public function run()
	{
		Activity::truncate();

        $this->faker = Faker::create();

        $users = User::all();

        $activities = ['sleep', 'work'];

        foreach($users as $user) {
            $this->user = $user;

            foreach ($activities as $activity) {
                $temp = new Activity(['name' => $activity]);
                $temp->user()->associate($user);
                $temp->save();
            }
        }

	}

}