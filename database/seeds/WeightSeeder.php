<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Weights\Weight;
use App\Models\Tags\Tag;
use App\Models\Tags\Taggable;
use Faker\Factory as Faker;
use Carbon\Carbon;

class WeightSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();
		Weight::truncate();
        $users = User::all();
		
		$faker = Faker::create();

        foreach($users as $user) {
            //Create a weight entry for the last 5 days
            foreach (range(0, 4) as $index) {

                Weight::create([
                    'date' => Carbon::today()->subDays($index)->format('Y-m-d'),
                    'weight' => $index / 10 + 50,
                    'user_id' => $user->id
                ]);
            }
        }
		
	}

}