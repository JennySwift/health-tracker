<?php

use App\Models\Exercises\Series as ExerciseSeries;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Exercises\Workout;

class WorkoutSeeder extends Seeder {

    private $user;

    public function run()
	{
		Workout::truncate();
        DB::table('series_workout')->truncate();
        $users = User::all();

        foreach($users as $user) {
            $this->user = $user;

            $seriesIds = ExerciseSeries::where('user_id', $this->user->id)
                ->lists('id')
                ->all();

            $this->createWorkout('day one', [$seriesIds[0], $seriesIds[1]]);
            $this->createWorkout('day two', $seriesIds[2]);
        }

	}

    /**
     *
     * @return Workout
     */
    private function createWorkout($name, $seriesIds)
    {
        $workout = new Workout([
            'name' => $name
        ]);

        $workout->user()->associate($this->user);
        $workout->save();

        //Attach the exercise series
        $workout->series()->attach($seriesIds);

        return $workout;
    }

}