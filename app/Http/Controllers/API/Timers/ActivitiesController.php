<?php

namespace App\Http\Controllers\API\Timers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\Timers\ActivityTransformer;
use App\Models\Timers\Activity;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    /**
     * @param Request $request
     * @return mixed
     * @internal param $date
     */
    public function calculateTotalMinutesForDay(Request $request)
    {
        $startOfDay = Carbon::createFromFormat('Y-m-d', $request->get('date'))->hour(0);
        $endOfDay = Carbon::createFromFormat('Y-m-d', $request->get('date'))->hour(24);

        $activitiesForDay = Activity::forCurrentUser()
            ->whereHas('timers', function ($q) use ($startOfDay, $endOfDay) {
                $q->where(function ($q) use ($startOfDay, $endOfDay) {
                    $q->whereBetween('start', [$startOfDay, $endOfDay])
                        ->orWhereBetween('finish', [$startOfDay, $endOfDay]);
                });
            })
            ->get();

        //For calculating total untracked time
        $totalMinutesForAllActivites = 0;

        foreach ($activitiesForDay as $activity) {
            $activity->totalMinutes = $activity->calculateMinutesForDay($startOfDay, $endOfDay);
            $totalMinutesForAllActivites += $activity->totalMinutes;
            $activity->hours = floor($activity->totalMinutes / 60);
            $activity->minutes = $activity->totalMinutes % 60;
            if ($activity->minutes < 10) {
                $activity->minutes = '0' . $activity->minutes;
            }
        }

        $untrackedTotalMinutes = 24 * 60 - $totalMinutesForAllActivites;
        $untrackedHours = floor($untrackedTotalMinutes / 60);
        $untrackedMinutes = $untrackedTotalMinutes % 60;
        if ($untrackedMinutes < 10) {
            $untrackedMinutes = '0' . $untrackedMinutes;
        }

        $activitiesForDay[] = [
            'name' => 'untracked',
            'totalMinutes' => $untrackedTotalMinutes,
            'hours' => $untrackedHours,
            'minutes' => $untrackedMinutes
        ];

        return $activitiesForDay;
    }
}
