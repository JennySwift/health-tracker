<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Units\Unit;

class UnitSeeder extends Seeder {

	public function run()
	{
		/**
		 * exercise units
		 */

		Unit::truncate();
        $users = User::all();

        foreach($users as $user) {
            Unit::create([
                'name' => 'reps',
                'for' => 'exercise',
                'user_id' => $user->id
            ]);

            Unit::create([
                'name' => 'minutes',
                'for' => 'exercise',
                'user_id' => $user->id
            ]);

            /**
             * food units
             */

            Unit::create([
                'name' => 'small',
                'for' => 'food',
                'user_id' => $user->id
            ]);

            Unit::create([
                'name' => 'medium',
                'for' => 'food',
                'user_id' => $user->id
            ]);

            Unit::create([
                'name' => 'large',
                'for' => 'food',
                'user_id' => $user->id
            ]);

            Unit::create([
                'name' => 'grams',
                'for' => 'food',
                'user_id' => $user->id
            ]);

            Unit::create([
                'name' => 'ounces',
                'for' => 'food',
                'user_id' => $user->id
            ]);

            Unit::create([
                'name' => 'kgs',
                'for' => 'food',
                'user_id' => $user->id
            ]);

            Unit::create([
                'name' => 'pounds',
                'for' => 'food',
                'user_id' => $user->id
            ]);

            Unit::create([
                'name' => 'bunch',
                'for' => 'food',
                'user_id' => $user->id
            ]);
        }
		

	}

}