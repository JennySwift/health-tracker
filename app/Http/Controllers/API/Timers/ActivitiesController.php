<?php

namespace App\Http\Controllers\API\Timers;

use App\Http\Transformers\ActivityTransformer;
use App\Models\Timers\Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Auth;

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

    /**
     * Todo: test
     * @param $date
     * @return mixed
     */
    public function calculateTotalMinutesForDay($date = null)
    {
        $startOfDay = Carbon::today()->hour(0);
        $endOfDay = Carbon::today()->hour(24);

        $activitiesForDay = Activity::forCurrentUser()
            ->whereHas('timers', function($q) use ($date, $startOfDay, $endOfDay)
            {
                $q->where(function($q) use ($startOfDay, $endOfDay)
                {
                    $q->whereBetween('start', [$startOfDay, $endOfDay])
                        ->orWhereBetween('finish', [$startOfDay, $endOfDay]);
                });
            })
            ->get();

        foreach ($activitiesForDay as $activity) {
            $activity->totalMinutes = $activity->calculateMinutesForDay($startOfDay, $endOfDay);
            $activity->hours = floor($activity->totalMinutes / 60);
            $activity->minutes = $activity->totalMinutes % 60;
            if ($activity->minutes < 10) {
                $activity->minutes = '0' . $activity->minutes;
            }
        }

        return $activitiesForDay;
    }
}
