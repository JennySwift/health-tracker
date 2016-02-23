<?php

namespace App\Http\Controllers\API\Timers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\Timers\TimerTransformer;
use App\Models\Timers\Activity;
use App\Models\Timers\Timer;
use App\Repositories\Timers\TimersRepository;
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
        //This bit is for the graphs
        if ($request->has('byDate')) {
            $entries = Timer::forCurrentUser()->get();

            return $this->timersRepository->getTimersInDateRange($entries);
        }

        else {
            //Return the timers for the date
            $entries = $this->timersRepository->getTimersOnDate($request->get('date'));
            $entries = $this->transform($this->createCollection($entries, new TimerTransformer(['date' => $request->get('date')])))['data'];

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
        $timer = new Timer($request->only([
            'start',
            'finish'
        ]));

        $timer->user()->associate(Auth::user());

        $activity = Activity::find($request->get('activity_id'));
        if (!$activity) {
            $activity = Activity::forCurrentUser()->where('name', 'sleep')->first();
        }

        $timer->activity()->associate($activity);
        $timer->save();

        $timer = $this->transform($this->createItem($timer, new TimerTransformer(['date' => $this->calculateFinishDate($timer)])))['data'];

        return response($timer, Response::HTTP_CREATED);
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
            'start',
            'finish'
        ]));

        $timer->update($data);

        if ($request->has('activity_id')) {
            $timer->activity()->associate(Activity::findOrFail($request->get('activity_id')));
            $timer->save();
        }

//        dd($timer);

        $finishDate = $this->calculateFinishDate($timer);

        $timer = $this->transform($this->createItem($timer, new TimerTransformer(['date' => $finishDate])))['data'];

        return response($timer, Response::HTTP_OK);
    }

    /**
     *
     * @param Timer $timer
     * @return null|string
     */
    private function calculateFinishDate(Timer $timer)
    {
        if ($timer->finish) {
            $finishDate = Carbon::createFromFormat('Y-m-d H:i:s', $timer->finish)->format('Y-m-d');
        }
        else {
            $finishDate = null;
        }

        return $finishDate;
    }

    /**
     * @return Response
     */
    public function checkForTimerInProgress()
    {
        $timerInProgress = Timer::forCurrentUser()->whereNull('finish')->first();

        if ($timerInProgress) {
            $timerInProgress = $this->transform($this->createItem($timerInProgress, new TimerTransformer))['data'];

            return response($timerInProgress, Response::HTTP_OK);
        }

        return response([], Response::HTTP_OK);
    }

    /**
     *
     * @param Timer $timer
     * @return Response
     * @throws \Exception
     */
    public function destroy(Timer $timer)
    {
        $timer->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }

}
