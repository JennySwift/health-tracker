<?php

namespace App\Http\Controllers\API\Timers;

use App\Http\Transformers\ActivityTransformer;
use App\Models\Timers\Activity;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ActivitiesController extends Controller
{

    /**
     *
     * @return Response
     */
    public function index()
    {
        $activities = Activity::forCurrentUser()->get();
        $activities = $this->transform($this->createCollection($activities, new ActivityTransformer))['data'];
        return response($activities, Response::HTTP_OK);
    }
}
