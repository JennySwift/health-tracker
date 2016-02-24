<?php

use App\Models\Exercises\ExerciseProgram;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;
use Illuminate\Support\Facades\Auth;

/**
 * Class ExerciseProgramSeeder
 */
class ExerciseProgramSeeder extends Seeder {

    /**
     * @var
     */
    private $user;
    private $faker;

    /**
     *
     */
    public function run()
	{
		ExerciseProgram::truncate();
        $this->faker = Faker::create();

        $users = User::all();

        foreach($users as $user) {
            $this->user = $user;

            $this->createPrograms();
        }
	}

    /**
     *
     */
    private function createPrograms()
    {
        foreach (Config::get('programs') as $tempProgram) {
            $program = new ExerciseProgram([
                'name' => $tempProgram
            ]);
            $program->user()->associate($this->user);
            $program->save();
        }
    }

}