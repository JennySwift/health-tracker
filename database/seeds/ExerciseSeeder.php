<?php

use App\Models\Exercises\ExerciseProgram;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Exercise;
use App\Models\Units\Unit;
use App\Models\Exercises\Series;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Auth;

class ExerciseSeeder extends Seeder {

    private $user;

    public function run()
	{
		Exercise::truncate();

		$pushups = ['kneeling pushups', 'pushups', 'one-arm pushups'];
		$squats = ['assisted squats', 'squats', 'one-legged-squats'];

        $users = User::all();

        foreach($users as $user) {
            $this->user = $user;

            $exercise_unit_ids = Unit::where('user_id', $this->user->id)
                ->where('for', 'exercise')
                ->lists('id')
                ->all();

            $this->insertExercisesInSeries(
                $pushups,
                Unit::find($exercise_unit_ids[0]),
                Series::where('user_id', $this->user->id)->where('name', 'pushup')->first()
            );

            $this->insertExercisesInSeries(
                $squats,
                Unit::find($exercise_unit_ids[1]),
                Series::where('user_id', $this->user->id)->where('name', 'squat')->first()
            );

        }

	}

    /**
     *
     * @param $series
     */
    private function insertExercisesInSeries($exercises, $unit, $series)
    {
        $index = 0;
        $faker = Faker::create();

//        $series_ids = Series::where('user_id', $this->user->id)->lists('id')->all();

        foreach ($exercises as $exercise) {
            $index++;
            $exercise = new Exercise([
                'name' => $exercise,
                'description' => $faker->word,
                'default_quantity' => 5,
                'step_number' => $index,
                'target' => 'something',
                'priority' => $faker->numberBetween(1,5)
            ]);

            $exercise->user()->associate($this->user);
            $exercise->defaultUnit()->associate($unit);
            $exercise->program()->associate(ExerciseProgram::first());

            $exercise->series()->associate($series);
            $exercise->save();

        }
    }

}