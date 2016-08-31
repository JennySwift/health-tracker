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
                'description' => '',
                'priority' => 2,
                'frequency' => 7
            ],
            [
                'name' => 'pushups',
                'defaultQuantity' => 10,
                'description' => 'hands shoulder width',
                'priority' => 1
            ],
            [
                'name' => 'one-arm pushups',
                'defaultQuantity' => 2,
                'description' => 'free hand behind back',
                'priority' => 1
            ]
        ];

		$squats = [
            [
                'name' => 'assisted squats',
                'defaultQuantity' => 50,
                'description' => 'hold onto something',
                'priority' => 3,
                'frequency' => 3
            ],
            [
                'name' => 'squats',
                'defaultQuantity' => 30,
                'description' => 'feet shoulder width',
                'priority' => 2
            ],
            [
                'name' => 'one-legged-squats',
                'defaultQuantity' => 5,
                'description' => '',
                'priority' => 1
            ]
        ];

        $gymnasticRings = [
            [
                'name' => 'back lever',
                'defaultQuantity' => 30,
                'description' => '',
                'priority' => 1,
                'frequency' => 4
            ],
        ];

        $flexibility = [
            [
                'name' => 'hamstrings',
                'defaultQuantity' => 20,
                'description' => '',
                'priority' => 2,
                'stretch' => 1,
                'frequency' => 7
            ],
            [
                'name' => 'calves',
                'defaultQuantity' => 10,
                'description' => 'great stretch',
                'priority' => 1,
                'stretch' => 1
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

            $this->insertExercisesInSeries(
                $gymnasticRings,
                Unit::find($exercise_unit_ids[1]),
                Series::where('user_id', $this->user->id)->where('name', 'gymnastic rings')->first()
            );

            $this->insertExercisesInSeries(
                $flexibility,
                Unit::find($exercise_unit_ids[1]),
                Series::where('user_id', $this->user->id)->where('name', 'flexibility')->first()
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
            $stretch = isset($exercise['stretch']) ? 1 : 0;
            $temp = new Exercise([
                'name' => $exercise['name'],
                'description' => $exercise['description'],
                'default_quantity' => $exercise['defaultQuantity'],
                'step_number' => $index,
                'target' => '3 * 10',
                'priority' => $exercise['priority'],
                'stretch' => $stretch
            ]);

            if (isset($exercise['frequency'])) $temp['frequency'] = $exercise['frequency'];

            $temp->user()->associate($this->user);
            $temp->defaultUnit()->associate($unit);
            $temp->program()->associate(ExerciseProgram::first());

            $temp->series()->associate($series);
            $temp->save();

        }
    }

}