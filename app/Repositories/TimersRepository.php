<?php

namespace App\Repositories;


use App\Models\Timers\Timer;
use Carbon\Carbon;

class TimersRepository {

    /**
     *
     * @param $entriesByDate
     * @param $date
     * @return int|string
     */
    public function getIndexOfItem($entriesByDate, $date)
    {
        foreach($entriesByDate as $key => $entry) {
            if ($entry['date'] === $date) {
                return $key;
            }
        }
    }

    /**
     * Sort entries by date
     * @param $entries
     * @return static
     */
    public function getTimersInDateRange($entries)
    {
        $formatForUser = 'D d/m/y';
        $earliestDate = Carbon::createFromFormat('Y-m-d H:i:s', Timer::forCurrentUser()->min('start'));
        $lastDate = Carbon::createFromFormat('Y-m-d H:i:s', Timer::forCurrentUser()->max('finish'));

        //Form an array with all the dates in the range of entries
        $entriesByDate = [];
        $index = 0;
        $shortDate = clone $lastDate;
        $shortDate = $shortDate->format('d/m');
        $day = clone $lastDate;
        $day = $day->format('D');
        $entriesByDate[] = [
            'date' => $lastDate->format($formatForUser),
            'orderIndex' => $index,
            'shortDate' => $shortDate,
            'day' => $day
        ];

        $date = Carbon::createFromFormat('Y-m-d H:i:s', Timer::forCurrentUser()->max('finish'));
        while ($date > $earliestDate) {
            $index++;
            $date = $date->subDays(1);
            $shortDate = clone $date;
            $shortDate = $shortDate->format('d/m');
            $day = clone $date;
            $day = $day->format('D');

            $entriesByDate[] = [
                'date' => $date->format($formatForUser),
                'shortDate' => $shortDate,
                'orderIndex' => $index,
                'day' => $day
            ];
        }

        //Add each entry to the array I formed
        foreach ($entries as $entry) {
            if ($entry->finish) {
                $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->format($formatForUser);
                $finishDate = Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish)->format($formatForUser);

                if ($startDate === $finishDate) {
                    $array = [
                        'start' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->format('g:ia'),
                        'finish' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->finish)->format('g:ia'),
                        'startPosition' => $entry->getStartRelativeHeight(),
                        'finishPosition' => $entry->getFinishRelativeHeight(),
                        'startHeight' => $entry->getDurationInMinutesDuringOneDay(),
                        'color' => $entry->activity->color
                    ];

                    $indexOfItem = $this->getIndexOfItem($entriesByDate, $startDate);
                    $entriesByDate[$indexOfItem][] = $array;
                }
                else {
                    $array = [
                        'start' => Carbon::createFromFormat('Y-m-d H:i:s', $entry->start)->format('g:ia'),
                        'finish' => null,
                        'startPosition' => $entry->getStartRelativeHeight(),
                        'finishPosition' => null,
                        'startHeight' => $entry->getDurationInMinutesDuringOneDay('finish'),
                        'color' => $entry->activity->color
                    ];

                    $indexOfItem = $this->getIndexOfItem($entriesByDate, $startDate);
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
                        'startHeight' => $entry->getDurationInMinutesDuringOneDay('start'),
                        'color' => $entry->activity->color
                    ];

                    $indexOfItem = $this->getIndexOfItem($entriesByDate, $finishDate);
                    $entriesByDate[$indexOfItem][] = $array;
                }
            }
        }

        return collect($entriesByDate)->reverse();
    }


}