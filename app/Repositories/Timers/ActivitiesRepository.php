<?php

namespace App\Repositories\Timers;

use App\Models\Timers\Activity;
use Carbon\Carbon;

class ActivitiesRepository {

    /**
     *
     * @param $startOfDay
     * @param $endOfDay
     * @return mixed
     */
    private function getActivitiesForDay($startOfDay, $endOfDay)
    {
        return Activity::forCurrentUser()
            ->whereHas('timers', function ($q) use ($startOfDay, $endOfDay) {
                $q->where(function ($q) use ($startOfDay, $endOfDay) {
                    $q->whereBetween('start', [$startOfDay, $endOfDay])
                        ->orWhereBetween('finish', [$startOfDay, $endOfDay]);
                });
            })
            ->get();
    }

    /**
     *
     * @param $date
     * @return array
     */
    public function calculateTotalMinutesForDay($date)
    {
        $startOfDay = Carbon::createFromFormat('Y-m-d', $date)->hour(0)->minute(0)->second(0);
        $endOfDay = Carbon::createFromFormat('Y-m-d', $date)->hour(24)->minute(0)->second(0);

        $activitiesForDay = $this->getActivitiesForDay($startOfDay, $endOfDay);

        //For calculating total untracked time
        $totalMinutesForAllActivities = 0;

        foreach ($activitiesForDay as $activity) {
            $activity->totalMinutesForDay($startOfDay, $endOfDay);
            $activity->hoursForDay();
            $activity->minutesForDay();

            $totalMinutesForAllActivities += $activity->totalMinutesForDay;
        }

        $activitiesForDay[] = $this->getUntrackedTimeForDay($totalMinutesForAllActivities);

        return $activitiesForDay;
    }

    /**
     *
     * @param $totalMinutesForDay
     */
    private function getUntrackedTimeForDay($totalMinutesForAllActivitiesForDay)
    {
        $untrackedTotalMinutesForDay = 24 * 60 - $totalMinutesForAllActivitiesForDay;
        $untrackedHoursForDay = floor($untrackedTotalMinutesForDay / 60);

        return [
            'name' => 'untracked',
            'totalMinutesForDay' => $untrackedTotalMinutesForDay,
            'hoursForDay' => $untrackedHoursForDay,
            'minutesForDay' => $untrackedTotalMinutesForDay % 60
        ];
    }

}