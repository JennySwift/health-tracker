<?php

use App\Models\Projects\Payee;
use App\Models\Projects\Payer;
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
		
		$john = User::where('name', 'John')->first();
        $payee_john = Payee::find($john->id);
        $payer_john = Payer::find($john->id);
		$jenny = User::where('name', 'Jenny')->first();
		$jane = User::where('name', 'Jane')->first();

        $bob = User::where('name', 'Bob')->first();
        $payee_bob = Payee::find($bob->id);

        /**
         * John is payee
         */

        foreach(range(0,2) as $index) {
            $project = Project::create([
                'payee_id' => $john->id,
                'payer_id' => $faker->randomElement($payee_john->payers()->lists('id')),
                'description' => $faker->word,
                'rate_per_hour' => 40
            ]);

            $this->createTimersForProject($project);
        }

        /**
         * John is payer
         */

        foreach(range(0,2) as $index) {
            $project = Project::create([
                'payee_id' => $faker->randomElement($payer_john->payees()->lists('id')),
                'payer_id' => $john->id,
                'description' => $faker->word,
                'rate_per_hour' => 1
            ]);

            $this->createTimersForProject($project);
        }

        /**
         * Bob is payee
         */

        foreach(range(0,2) as $index) {
            $project = Project::create([
                'payee_id' => $bob->id,
                'payer_id' => $jenny->id,
                'description' => $faker->word,
                'rate_per_hour' => 40
            ]);

            $this->createTimersForProject($project);
        }
	}

    private function createTimersForProject($project)
    {
        $faker = Faker::create();

        foreach (range(0, 8) as $index) {
//            $finish = $faker->dateTimeBetween($startDate = '-1 days', $endDate = 'now');
//            $start = $faker->dateTimeBetween($startDate = '-1 days', $endDate = $finish);

            $minutes = $faker->randomElement(['01', '02', '05', '10', '20', '30', '45', '50']);
            $finish = '2015-06-02 12:' . $minutes . ':00';
            $start = '2015-06-02 12:00:00';

            $time = $this->calculateTimerTime($start, $finish);
            $price = $this->getTimerPrice($time, $project->rate_per_hour);

            $timer = new Timer([
                'start' => $start,
                'finish' => $finish,
                'price' => $price,
//                'paid' => $faker->boolean($chanceOfGettingTrue = 50)
            ]);

            $project->timers()->save($timer);
        }
    }

    private function calculateTimerTime($start, $finish)
    {
        $carbon_start = Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $carbon_finish = Carbon::createFromFormat('Y-m-d H:i:s', $finish);
        $time = $carbon_finish->diff($carbon_start);
        return $time;
    }

    private function getTimerPrice($time, $rate)
    {
        $price = 0;

        if ($time->s > 30) {
            $time->i = $time->i + 1;
        }

        $price+= $rate * $time->h;
        $price+= $rate / 60 * $time->i;

        return $price;
    }

}