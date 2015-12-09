<?php

namespace App\Http\Controllers\API\Timers;

use App\Http\Transformers\SleepTransformer;
use App\Http\Transformers\TimerTransformer;
use App\Models\Sleep\Sleep;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Timers\Activity;
use App\Models\Timers\Timer;
use App\Repositories\SleepRepository;
use App\Repositories\TimersRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TimersController extends Controller
{
    /**
     * @var TimersRepository
     */
    private $timersRepository;

    /**
     * @param TimersRepository $timersRepository
     */
    public function __construct(TimersRepository $timersRepository)
    {
        $this->timersRepository = $timersRepository;
    }

    /**
     *
     * @param Request $request
     * @return Response|static
     */
    public function index(Request $request)
    {
        if($request->has('byDate')) {
            $entries = Timer::forCurrentUser()->get();
            return $this->timersRepository->getTimersInDateRange($entries);
        }

        else {
            //Return the timers for today
            $entries = Timer::forCurrentUser()->whereDate('finish', '=', Carbon::today()->format('Y-m-d'))->get();
            $entries = $this->transform($this->createCollection($entries, new TimerTransformer))['data'];
            return response($entries, Response::HTTP_OK);
        }
    }

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $sleep = new Timer($request->only(['start', 'finish']));
        $sleep->user()->associate(Auth::user());

        $activity = Activity::find($request->get('activity_id'));
        if (!$activity) {
            $activity = Activity::forCurrentUser()->where('name', 'sleep')->first();
        }

        $sleep->activity()->associate($activity);
        $sleep->save();
    
        $sleep = $this->transform($this->createItem($sleep, new TimerTransformer))['data'];
        return response($sleep, Response::HTTP_CREATED);
    }

    /**
     *
     * @param Request $request
     * @param Timer $timer
     * @return Response
     */
    public function update(Request $request, Timer $timer)
    {
        // Create an array with the new fields merged
        $data = array_compare($timer->toArray(), $request->only([
            'start', 'finish'
        ]));
    
        $timer->update($data);
    
        if ($request->has('activity_id')) {
            $timer->activity()->associate(Activity::findOrFail($request->get('activity_id')));
            $timer->save();
        }

//        dd($timer);
    
        $timer = $this->transform($this->createItem($timer, new TimerTransformer))['data'];
        return response($timer, Response::HTTP_OK);
    }

}
