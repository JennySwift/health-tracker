<?php

namespace App\Http\Controllers\API\Sleep;

use App\Http\Transformers\SleepTransformer;
use App\Models\Sleep\Sleep;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SleepController extends Controller
{
    public function index()
    {
        $sleep = Sleep::forCurrentUser()->get();
        $sleep = $this->transform($this->createCollection($sleep, new SleepTransformer))['data'];
        return response($sleep, Response::HTTP_OK);
    }
}
