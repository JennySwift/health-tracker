<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {

	public function run()
	{
		User::truncate();

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
	}

}