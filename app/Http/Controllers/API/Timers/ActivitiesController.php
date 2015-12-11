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
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $activity = new Activity($request->only(['name', 'color']));
        $activity->user()->associate(Auth::user());
        $activity->save();
    
        $activity = $this->transform($this->createItem($activity, new ActivityTransformer))['data'];
        return response($activity, Response::HTTP_CREATED);
    }

    /**
     *
     * @param Request $request
     * @param Activity $activity
     * @return Response
     */
    public function update(Request $request, Activity $activity)
    {
        // Create an array with the new fields merged
        $data = array_compare($activity->toArray(), $request->only([
            'name', 'color'
        ]));

        $activity->update($data);

        $activity = $this->transform($this->createItem($activity, new ActivityTransformer))['data'];
        return response($activity, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return mixed
     * @internal param $date
     */
    public function calculateTotalMinutesForDay(Request $request)
    {
        $date = $request->get('date');
        $startOfDay = Carbon::createFromFormat('Y-m-d', $date)->hour(0)->minute(0)->second(0);
        $endOfDay = Carbon::createFromFormat('Y-m-d', $date)->hour(24)->minute(0)->second(0);

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

    /**
     *
     * @param Activity $activity
     * @return Response
     */
    public function destroy(Activity $activity)
    {
        try {
            $activity->delete();
            return response([], Response::HTTP_NO_CONTENT);
        }
        catch (\Exception $e) {
            //Integrity constraint violation
            if ($e->getCode() === '23000') {
                $message = 'Activity could not be deleted. It is in use.';
            }
            else {
                $message = 'There was an error';
            }
            return response([
                'error' => $message,
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
