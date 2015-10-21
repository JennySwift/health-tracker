<?php namespace App\Http\Controllers\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Exercises\Workout;
use Auth;
use Illuminate\Http\Request;

/**
 * Class WorkoutsController
 * @package App\Http\Controllers\Exercises
 */
class WorkoutsController extends Controller
{

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $name = $request->get('name');

        Workout::insert([
            'name' => $name,
            'user_id' => Auth::user()->id
        ]);

        return Workout::getWorkouts();
    }
}