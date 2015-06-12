<?php

use App\Models\Projects\Payee;
use App\Models\Projects\Payer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {

	public function run()
	{
		User::truncate();
        DB::table('payee_payer')->truncate();

		$jenny = User::create([
			'name' => 'Jenny',
			'email' => 'cheezyspaghetti@gmail.com',
			'password' => bcrypt('abcdefg')
		]);

        $jane = User::create([
            'name' => 'Jane',
            'email' => 'jane@someplace.com',
            'password' => bcrypt('abcdefg')
        ]);

        $bob = User::create([
            'name' => 'Bob',
            'email' => 'bob@someplace.com',
            'password' => bcrypt('abcdefg')
        ]);

        $john = User::create([
            'name' => 'John',
            'email' => 'cheezyspaghetti@optusnet.com.au',
            'password' => bcrypt('abcdefg')
        ]);

        /**
         * Create payers/payees for John
         */
        $payee_john = Payee::find($john->id);
        $payer_john = Payer::find($john->id);

        $payee_john->payers()->attach($jenny->id);
        $payee_john->payers()->attach($jane->id);
        $payer_john->payees()->attach($bob->id);
        $payee_john->save();
        $payer_john->save();
	}

}