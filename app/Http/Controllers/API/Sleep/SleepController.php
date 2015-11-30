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

        if($request->has('byDate')) {
//            //Sort entries by date
            $earliestDate = Carbon::createFromFormat('Y-m-d H:i:s', Sleep::forCurrentUser()->min('start'))->format('Y-m-d');
            $lastDate = Carbon::createFromFormat('Y-m-d H:i:s', Sleep::forCurrentUser()->max('finish'))->format('Y-m-d');

            //Form an array with all the dates in the range of entries
            $entriesByDate = [];
            $entriesByDate[$lastDate] = [
                'date' => $lastDate
            ];

            $date = Carbon::createFromFormat('Y-m-d H:i:s', Sleep::forCurrentUser()->max('finish'));
            while ($date > $earliestDate) {
                $date = $date->subDays(1);
                $entriesByDate[$date->format('Y-m-d')] = [
                    'date' => $date->format('Y-m-d')
                ];
            }

            //Add each entry to the array I formed
            foreach ($entries as $entry) {
                $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->format('Y-m-d');
                $finishDate = Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish)->format('Y-m-d');

                if ($startDate === $finishDate) {
                    $entriesByDate[$startDate][] = [
                        'start' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->format('d M y g:ia'),
                        'finish' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish)->format('d M y g:ia')
                    ];
                }
                else {
                    $entriesByDate[$startDate][] = [
                        'start' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->format('d M y g:ia'),
                        'finish' => null
                    ];
                    $entriesByDate[$finishDate][] = [
                        'start' => null,
                        'finish' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish)->format('d M y g:ia'),
                    ];
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
}
