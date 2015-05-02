<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Entry as ExerciseEntry;
use Faker\Factory as Faker;

class ExerciseEntrySeeder extends Seeder {

	public function run()
	{
		ExerciseEntry::truncate();
		
		$faker = Faker::create();

		ExerciseEntry::create([
			'date' => '2015-05-02',
			'exercise_id' => 1,
			'quantity' => 15,
			'exercise_unit_id' => 1,
			'user_id' => 1
		]);
	}

}