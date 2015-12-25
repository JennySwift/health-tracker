<?php

namespace App\Http\Controllers\API\Exercises;

use App\Http\Transformers\ExerciseProgramTransformer;
use App\Models\Exercises\ExerciseProgram;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Auth;

class ExerciseProgramsController extends Controller
{

    /**
     *
     * @return Response
     */
    public function index()
    {
        $programs = ExerciseProgram::forCurrentUser()->get();
        $programs = $this->transform($this->createCollection($programs, new ExerciseProgramTransformer))['data'];
        return response($programs, Response::HTTP_OK);
    }

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $exerciseProgram = new ExerciseProgram($request->only(['name']));
        $exerciseProgram->user()->associate(Auth::user());
        $exerciseProgram->save();

        $exerciseProgram = $this->transform($this->createItem($exerciseProgram, new ExerciseProgramTransformer))['data'];
        return response($exerciseProgram, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
