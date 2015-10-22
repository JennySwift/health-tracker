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
        $workout = new Workout([
            'name' => $request->get('name')
        ]);

        $workout->user()->associate(Auth::user());
        $workout->save();

        return $this->responseCreated($workout);
    }
}