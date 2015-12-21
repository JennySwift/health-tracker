<?php

namespace App\Http\Controllers\API\Timers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\Timers\ActivityTransformer;
use App\Models\Timers\Activity;
use App\Repositories\Timers\ActivitiesRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActivitiesController extends Controller
{
    /**
     * @var ActivitiesRepository
     */
    private $activitiesRepository;

    /**
     * ActivitiesController constructor.
     * @param ActivitiesRepository $activitiesRepository
     */
    public function __construct(ActivitiesRepository $activitiesRepository)
    {
        $this->activitiesRepository = $activitiesRepository;
    }

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
     *
     * @param Request $request
     * @return array
     */
    public function calculateTotalMinutesForDay(Request $request)
    {
        return $this->activitiesRepository->calculateTotalMinutesForDay($request->get('date'));
    }

    /**
     *
     * @param Request $request
     * @return array
     */
    public function calculateTotalMinutesForWeek(Request $request)
    {
        return $this->activitiesRepository->calculateTotalMinutesForWeek($request->get('date'));
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
