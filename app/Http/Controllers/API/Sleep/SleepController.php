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
        $formatForUser = 'd/m/y';

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
                        'startRelativeHeight' => $this->getStartRelativeHeight($entry),
                        'finishRelativeHeight' => $this->getFinishRelativeHeight($entry),
                        'durationInMinutes' => $this->getDurationInMinutes($entry)
                    ];

                    $indexOfItem = $this->getIndexOfItem($entriesByDate, $startDate);
                    $entriesByDate[$indexOfItem][] = $array;
                }
                else {
                    $array = [
                        'start' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->format('g:ia'),
                        'finish' => null,
                        'startRelativeHeight' => $this->getStartRelativeHeight($entry),
                        'finishRelativeHeight' => null,
                    ];

                    $indexOfItem = $this->getIndexOfItem($entriesByDate, $startDate);
                    $entriesByDate[$indexOfItem][] = $array;

                    $array = [
                        'start' => null,
                        'finish' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish)->format('g:ia'),
                        'startRelativeHeight' => null,
                        'finishRelativeHeight' => $this->getFinishRelativeHeight($entry)
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
     * @return int
     */
    private function getDurationInMinutes($entry)
    {
        $finish = Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish);
        return $finish->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $entry->start));
    }
    /**
     *
     * @param $entry
     * @return int
     */
    private function getStartRelativeHeight($entry)
    {
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
