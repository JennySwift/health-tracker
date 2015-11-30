<?php

namespace App\Http\Controllers\API\Sleep;

use App\Http\Transformers\SleepTransformer;
use App\Models\Sleep\Sleep;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SleepController extends Controller
{
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
                        'startPosition' => $this->getStartRelativeHeight($entry),
                        'finishPosition' => $this->getFinishRelativeHeight($entry),
                        'startHeight' => $this->getDurationInMinutes($entry)
                    ];

                    $indexOfItem = $this->getIndexOfItem($entriesByDate, $startDate);
                    $entriesByDate[$indexOfItem][] = $array;
                }
                else {
                    $array = [
                        'start' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->format('g:ia'),
                        'finish' => null,
                        'startPosition' => $this->getStartRelativeHeight($entry),
                        'finishPosition' => null,
                        'startHeight' => $this->getDurationInMinutes($entry, 'finish')
                    ];

                    $indexOfItem = $this->getIndexOfItem($entriesByDate, $startDate);
                    $entriesByDate[$indexOfItem][] = $array;

                    $finish = $this->getFinish($entry);
                    $midnight = clone $finish;
                    $midnight = $midnight->hour(0)->minute(0);

                    $array = [
                        'start' => null,
                        'fakeStart' => $midnight->format('g:ia'),
                        'fakeStartPosition' => $this->getStartRelativeHeight($entry, true),
                        'finish' => $finish->format('g:ia'),
                        'startPosition' => null,
                        'finishPosition' => $this->getFinishRelativeHeight($entry),
                        'startHeight' => $this->getDurationInMinutes($entry, 'start')
                    ];

                    $indexOfItem = $this->getIndexOfItem($entriesByDate, $finishDate);
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
     * @param $entry
     * @return static
     */
    private function getFinish($entry)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish);
    }

    /**
     *
     * @param $entriesByDate
     * @param $date
     * @return int|string
     */
    private function getIndexOfItem($entriesByDate, $date)
    {
        foreach($entriesByDate as $key => $entry) {
            if ($entry['date'] === $date) {
                return $key;
            }
        }
    }

    /**
     *
     * @param $entry
     * @param bool $nullValue
     * @return int
     */
    private function getDurationInMinutes($entry, $nullValue = false)
    {
        if (!$nullValue) {
            //Start and finish times are on the same day
            $finish = Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish);
            return $finish->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $entry->start));
        }
        else if ($nullValue === 'start') {
            //The entry was finished on one day and started on an earlier day,
            //so calculate the time from the finish till the most recent midnight
            $finish = Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish);
            $midnight = clone $finish;
            $midnight = $midnight->hour(0)->minute(0);
            return $finish->diffInMinutes($midnight);
        }
        else if ($nullValue === 'finish') {
            //The entry was started on one day and finished on a later day,
            //so calculate the time from the start till midnight
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $entry->start);
            $midnight = clone $start;
            $midnight = $midnight->hour(24)->minute(0);
            return $start->diffInMinutes($midnight);
        }

    }

    /**
     *
     * @param $entry
     * @param bool $fakeStart
     * @return int
     */
    private function getStartRelativeHeight($entry, $fakeStart = false)
    {
        if ($fakeStart) {
            return 0;
        }
        return Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->hour(0)->minute(0));
    }

    /**
     *
     * @param $entry
     * @return int
     */
    private function getFinishRelativeHeight($entry)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish)->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish)->hour(0)->minute(0));
    }
}
