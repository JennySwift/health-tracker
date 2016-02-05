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
    private $faker;

    public function run()
	{
		Exercise::truncate();
        $this->faker = Faker::create();

		$pushups = [
            [
                'name' => 'kneeling pushups',
                'defaultQuantity' => 20,
                'description' => $this->faker->word,
                'priority' => 2
            ],
            [
                'name' => 'pushups',
                'defaultQuantity' => 10,
                'description' => $this->faker->word,
                'priority' => 1
            ],
            [
                'name' => 'one-arm pushups',
                'defaultQuantity' => 2,
                'description' => $this->faker->word,
                'priority' => 1
            ]
        ];

		$squats = [
            [
                'name' => 'assisted squats',
                'defaultQuantity' => 50,
                'description' => $this->faker->word,
                'priority' => 3
            ],
            [
                'name' => 'squats',
                'defaultQuantity' => 30,
                'description' => '',
                'priority' => 2
            ],
            [
                'name' => 'one-legged-squats',
                'defaultQuantity' => 5,
                'description' => $this->faker->word,
                'priority' => 1
            ]
        ];

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
     * @param $exercises
     * @param Unit $unit
     * @param Series $series
     */
    private function insertExercisesInSeries($exercises, Unit $unit, Series $series)
    {
        $index = 0;

//        $series_ids = Series::where('user_id', $this->user->id)->lists('id')->all();

        foreach ($exercises as $exercise) {
            $index++;
            $exercise = new Exercise([
                'name' => $exercise['name'],
                'description' => $exercise['description'],
                'default_quantity' => $exercise['defaultQuantity'],
                'step_number' => $index,
                'target' => 'something',
                'priority' => $exercise['priority']
            ]);

            $exercise->user()->associate($this->user);
            $exercise->defaultUnit()->associate($unit);
            $exercise->program()->associate(ExerciseProgram::first());

            $exercise->series()->associate($series);
            $exercise->save();

        }
    }

}