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
            $entriesByDate[$lastDate->format($formatForUser)] = [
                'date' => $lastDate->format($formatForUser)
            ];

            $date = Carbon::createFromFormat('Y-m-d H:i:s', Sleep::forCurrentUser()->max('finish'));
            while ($date > $earliestDate) {
                $date = $date->subDays(1);

                $entriesByDate[$date->format($formatForUser)] = [
                    'date' => $date->format($formatForUser)
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

                    $entriesByDate[$startDate][] = $array;
                }
                else {
                    $array = [
                        'start' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->format('g:ia'),
                        'finish' => null,
                        'startRelativeHeight' => $this->getStartRelativeHeight($entry),
                        'finishRelativeHeight' => null,
                    ];

                    $entriesByDate[$startDate][] = $array;

                    $array = [
                        'start' => null,
                        'finish' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish)->format('g:ia'),
                        'startRelativeHeight' => null,
                        'finishRelativeHeight' => $this->getFinishRelativeHeight($entry)
                    ];

                    $entriesByDate[$finishDate][] = $array;
                }
            }

            return $entriesByDate;


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
