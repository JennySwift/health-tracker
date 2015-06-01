<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;

class UserSeeder extends Seeder {

	public function run()
	{
		User::truncate();
		
		User::create([
			'name' => 'John',
			'email' => 'cheezyspaghetti@gmail.com',
			'password' => bcrypt('abcdefg')
		]);

		User::create([
			'name' => 'Jane',
			'email' => 'cheezyspaghetti@optusnet.com.au',
			'password' => bcrypt('hijklmnop')
		]);
	}

}