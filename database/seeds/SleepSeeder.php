<?php

use App\Models\Exercises\Series as ExerciseSeries;
use App\Models\Sleep\Sleep;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Workout;

class SleepSeeder extends Seeder {

    private $user;

    public function run()
	{
		Sleep::truncate();

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
                    'finish' => $date->addDays(1)->hour(8)->format('Y-m-d H:i:s')
                ]);

                $entry->user()->associate($this->user);
                $entry->save();
            }
        }

	}

}