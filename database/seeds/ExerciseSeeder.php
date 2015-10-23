<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Exercise;
use App\Models\Units\Unit;
use App\Models\Exercises\Series;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Auth;

class ExerciseSeeder extends Seeder {

	public function run()
	{
		Exercise::truncate();

		$faker = Faker::create();

		$pushup_series = ['kneeling pushups', 'pushups', 'one-arm pushups'];
		$squat_series = ['assisted squats', 'squats', 'one-legged-squats'];

        $users = User::all();

        foreach($users as $user) {
            $index = 0;
            $exercise_unit_ids = Unit::where('user_id', $user->id)->where('for', 'exercise')->lists('id')->all();
            $series_ids = Series::where('user_id', $user->id)->lists('id')->all();

            foreach ($pushup_series as $exercise) {
                $index++;
                DB::table('exercises')->insert([
                    'name' => $exercise,
                    'default_unit_id' => $faker->randomElement($exercise_unit_ids),
                    'description' => $faker->word,
                    'default_quantity' => 5,
                    'step_number' => $index,
                    'series_id' => 1,
                    'user_id' => $user->id
                ]);
            }

            $index = 0;
            foreach ($squat_series as $exercise) {
                $index++;
                DB::table('exercises')->insert([
                    'name' => $exercise,
                    'default_unit_id' => $faker->randomElement($exercise_unit_ids),
                    'description' => $faker->word,
                    'default_quantity' => 5,
                    'step_number' => $index,
                    'series_id' => 3,
                    'user_id' => $user->id
                ]);
            }
        }

	}

}