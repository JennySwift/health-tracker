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
        $entries = Timer::forCurrentUser()->get();
        $formatForUser = 'D d/m/y';

        if($request->has('byDate')) {
//            //Sort entries by date
//            return $entries;
            $earliestDate = Carbon::createFromFormat('Y-m-d H:i:s', Timer::forCurrentUser()->min('start'));
            $lastDate = Carbon::createFromFormat('Y-m-d H:i:s', Timer::forCurrentUser()->max('finish'));

            //Form an array with all the dates in the range of entries
            $entriesByDate = [];
            $index = 0;
            $entriesByDate[] = [
                'date' => $lastDate->format($formatForUser),
                'orderIndex' => $index
            ];

            $date = Carbon::createFromFormat('Y-m-d H:i:s', Timer::forCurrentUser()->max('finish'));
            while ($date > $earliestDate) {
                $index++;
                $date = $date->subDays(1);

                $entriesByDate[] = [
                    'date' => $date->format($formatForUser),
                    'orderIndex' => $index
                ];
            }

            //Add each entry to the array I formed
            foreach ($entries as $entry) {
                $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->format($formatForUser);
                $finishDate = Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish)->format($formatForUser);

                if ($startDate === $finishDate) {
                    $array = [
                        'start' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->format('g:ia'),
                        'finish' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish)->format('g:ia'),
                        'startPosition' => $entry->getStartRelativeHeight(),
                        'finishPosition' => $entry->getFinishRelativeHeight(),
                        'startHeight' => $entry->getDurationInMinutesDuringOneDay()
                    ];

                    $indexOfItem = $this->timersRepository->getIndexOfItem($entriesByDate, $startDate);
                    $entriesByDate[$indexOfItem][] = $array;
                }
                else {
                    $array = [
                        'start' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->format('g:ia'),
                        'finish' => null,
                        'startPosition' => $entry->getStartRelativeHeight(),
                        'finishPosition' => null,
                        'startHeight' => $entry->getDurationInMinutesDuringOneDay('finish')
                    ];

                    $indexOfItem = $this->timersRepository->getIndexOfItem($entriesByDate, $startDate);
                    $entriesByDate[$indexOfItem][] = $array;

                    $finish = $entry->getFinish();
                    $midnight = clone $finish;
                    $midnight = $midnight->hour(0)->minute(0);

                    $array = [
                        'start' => null,
                        'fakeStart' => $midnight->format('g:ia'),
                        'fakeStartPosition' => $entry->getStartRelativeHeight(true),
                        'finish' => $finish->format('g:ia'),
                        'startPosition' => null,
                        'finishPosition' => $entry->getFinishRelativeHeight(),
                        'startHeight' => $entry->getDurationInMinutesDuringOneDay('start')
                    ];

                    $indexOfItem = $this->timersRepository->getIndexOfItem($entriesByDate, $finishDate);
                    $entriesByDate[$indexOfItem][] = $array;
                }
            }

            return collect($entriesByDate)->reverse();


        }

        else {
            //Each sleep entry is separate
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

}
