<?php

namespace App\Http\Controllers\API\Sleep;

use App\Http\Transformers\SleepTransformer;
use App\Models\Sleep\Sleep;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\SleepRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SleepController extends Controller
{
    /**
     * @var SleepRepository
     */
    private $sleepRepository;

    /**
     * @param SleepRepository $sleepRepository
     */
    public function __construct(SleepRepository $sleepRepository)
    {
        $this->sleepRepository = $sleepRepository;
    }

    /**
     *
     * @param Request $request
     * @return Response|static
     */
    public function index(Request $request)
    {
        $entries = Sleep::forCurrentUser()->get();
        $formatForUser = 'D d/m/y';

        if($request->has('byDate')) {
//            //Sort entries by date
//            return $entries;
            $earliestDate = Carbon::createFromFormat('Y-m-d H:i:s', Sleep::forCurrentUser()->min('start'));
            $lastDate = Carbon::createFromFormat('Y-m-d H:i:s', Sleep::forCurrentUser()->max('finish'));

            //Form an array with all the dates in the range of entries
            $entriesByDate = [];
            $index = 0;
            $entriesByDate[] = [
                'date' => $lastDate->format($formatForUser),
                'orderIndex' => $index
            ];

            $date = Carbon::createFromFormat('Y-m-d H:i:s', Sleep::forCurrentUser()->max('finish'));
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
                        'startHeight' => $entry->getDurationInMinutes()
                    ];

                    $indexOfItem = $this->sleepRepository->getIndexOfItem($entriesByDate, $startDate);
                    $entriesByDate[$indexOfItem][] = $array;
                }
                else {
                    $array = [
                        'start' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->format('g:ia'),
                        'finish' => null,
                        'startPosition' => $entry->getStartRelativeHeight(),
                        'finishPosition' => null,
                        'startHeight' => $entry->getDurationInMinutes('finish')
                    ];

                    $indexOfItem = $this->sleepRepository->getIndexOfItem($entriesByDate, $startDate);
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
                        'startHeight' => $entry->getDurationInMinutes('start')
                    ];

                    $indexOfItem = $this->sleepRepository->getIndexOfItem($entriesByDate, $finishDate);
                    $entriesByDate[$indexOfItem][] = $array;
                }
            }

            return collect($entriesByDate)->reverse();


        }

        else {
            //Each sleep entry is separate
            $entries = $this->transform($this->createCollection($entries, new SleepTransformer))['data'];
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
        $sleep = new Sleep($request->only(['start', 'finish']));
        $sleep->user()->associate(Auth::user());
        $sleep->save();
    
        $sleep = $this->transform($this->createItem($sleep, new SleepTransformer))['data'];
        return response($sleep, Response::HTTP_CREATED);
    }

}
