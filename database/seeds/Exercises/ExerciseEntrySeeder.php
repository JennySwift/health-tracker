<?php

use App\Models\Exercises\Entry;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Entry as ExerciseEntry;
use App\Models\Exercises\Exercise;
use App\Models\Units\Unit;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ExerciseEntrySeeder extends Seeder {

    private $user;
    private $exercise_ids;
    private $date;
    private $faker;

    public function run()
	{
        ExerciseEntry::truncate();
        $this->faker = Faker::create();
        $users = User::all();

        foreach ($users as $user) {
            $this->user = $user;

            $this->unit_ids = Unit::where('user_id', $this->user->id)
                ->where('for', 'exercise')
                ->lists('id')
                ->all();

            $this->exercise_ids = Exercise::where('user_id', $this->user->id)
                ->lists('id')
                ->all();

            $this->createEntriesForTheLastFiveDays();
        }
    }

    /**
     * Create a few exercise entries for each of the last 5 days
     */
    private function createEntriesForTheLastFiveDays()
    {
        foreach (range(0, 4) as $index) {
            $today = Carbon::today();
            $this->date = $today->subDays($index);

            if ($this->date == Carbon::today()) {
                $this->createEntriesForToday();
            }

            else if ($this->date == Carbon::today()->subdays(1)) {
                //Add assisted squat entries for 1 day ago
                $this->insertExerciseSet(Exercise::forCurrentUser()->where('name', 'assisted squats')->first(), Carbon::today()->subDays(1));
            }

            else if ($this->date == Carbon::today()->subdays(2)) {
                //Add back lever entries for 2 days ago
                $this->insertExerciseSet(Exercise::forCurrentUser()->where('name', 'back lever')->first(), Carbon::today()->subDays(2));
            }

            else {
                $this->createEntriesForOneDay();
            }
        }
    }

    /**
     * Create a few entries for each of a few different exercises (no duplicates).
     * Ideally, a random number of different exercises.
     * @param $date
     */
    private function createEntriesForOneDay()
    {
        $random_exercise_ids = $this->faker->randomElements($this->exercise_ids, $count = 3);

        //Insert a few sets for each $random_exercise_id
        foreach ($random_exercise_ids as $exercise_id) {
            $this->insertExerciseSet(Exercise::find($exercise_id), $this->date);
        }
    }

    /**
     * Insert the same exercise with the same unit a
     * few times so we have exercise sets.
     * @param Exercise $exercise
     * @param Carbon $date
     */
    private function insertExerciseSet(Exercise $exercise, Carbon $date)
    {
        $unit = Unit::find($this->unit_ids[0]);

        $this->createEntry(
            $this->faker->numberBetween($min = 4, $max = 30),
            $exercise,
            $unit,
            $date
        );

        $this->createEntry(
            $this->faker->numberBetween($min = 4, $max = 30),
            $exercise,
            $unit,
            $date
        );
    }

    /**
     *
     * @param $quantity
     * @param Exercise $exercise
     * @param Unit $unit
     * @param Carbon $date
     */
    private function createEntry($quantity, Exercise $exercise, Unit $unit, Carbon $date)
    {
        $entry = new Entry([
            'date' => $date->format('Y-m-d'),
            'quantity' => $quantity,
        ]);

        $entry->user()->associate($this->user);
        $entry->unit()->associate($unit);
        $entry->exercise()->associate($exercise);
        $entry->save();
    }

    /**
     * Create the entries for the same exercise but with different units
     * for today, so that I can test out the getSpecificExerciseEntries
     * for a day where the exercise is entered with different units
     */
    private function createEntriesForToday()
    {
        $exercise = Exercise::where('user_id', $this->user->id)->first();
        $date = Carbon::today();

        $this->createEntry(5, $exercise, Unit::find($this->unit_ids[0]), $date);
        $this->createEntry(5, $exercise, Unit::find($this->unit_ids[0]), $date);
        $this->createEntry(10, $exercise, Unit::find($this->unit_ids[1]), $date);
    }
}