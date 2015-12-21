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
    public function calculateTotalMinutesForAllActivitiesForDay($date)
    {
        $startOfDay = Carbon::createFromFormat('Y-m-d', $date)->hour(0)->minute(0)->second(0);
        $endOfDay = Carbon::createFromFormat('Y-m-d', $date)->hour(24)->minute(0)->second(0);

        $activitiesForDay = $this->getActivitiesForDay($startOfDay, $endOfDay);

        //For calculating total untracked time
        $totalMinutesForAllActivities = 0;

        foreach ($activitiesForDay as $activity) {
            $activity->totalMinutesForDay = $activity->calculateTotalMinutesForDay($startOfDay, $endOfDay);

            $totalMinutesForAllActivities += $activity->totalMinutesForDay;
        }

        $activitiesForDay[] = $this->getUntrackedTimeForDay($totalMinutesForAllActivities);

        return $activitiesForDay;
    }

    /**
     *
     * @param $date
     * @return array
     */
    public function calculateTotalMinutesForWeek($date)
    {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        $startOfWeek = Carbon::createFromFormat('Y-m-d', $date)->startOfWeek();
        $endOfWeek = Carbon::createFromFormat('Y-m-d', $date)->endOfWeek();

        //For calculating total untracked time
        $totalMinutesForAllActivities = 0;

        $activities = Activity::forCurrentUser()->get();

        foreach ($activities as $activity) {
            $activity->totalMinutesForWeek = $activity->calculateTotalMinutesForWeek($startOfWeek, $endOfWeek);
            $activity->totalMinutesForAllTime();
            $activity->averageMinutesPerDayForWeek = $activity->calculateAverageMinutesPerDayForWeek($date);
            $totalMinutesForAllActivities += $activity->totalMinutesForWeek;
        }

        $activities[] = $this->getUntrackedTimeForWeek($totalMinutesForAllActivities, $date);

        return $activities;
    }

    /**
     * Instead of subtracting the totalMinutesForAllActivitesForWeek
     * from the total minutes in week,
     * subtract from the total minutes from Sunday
     * till the end of the current day.
     * Actually, that didn't turn out well. So just subtract from
     * the total minutes in a week.
     * @param $totalMinutesForAllActivitiesForWeek
     * @param $date
     * @return array
     */
    private function getUntrackedTimeForWeek($totalMinutesForAllActivitiesForWeek, $date)
    {
        //Sunday === 0, Monday === 1, etc
        $dayNumber = Carbon::createFromFormat('Y-m-d', $date)->dayOfWeek;

//        dd(24 * 60 * ($dayNumber + 1), $totalMinutesForAllActivitiesForWeek);

//        $untrackedTotalMinutesForWeek = 24 * 60 * ($dayNumber + 1) - $totalMinutesForAllActivitiesForWeek;
        $untrackedTotalMinutesForWeek = 24 * 60 * 7 - $totalMinutesForAllActivitiesForWeek;

        return [
            'name' => 'untracked',
            'totalMinutesForWeek' => $untrackedTotalMinutesForWeek,
        ];
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
            'name' => 'untracked till end of day',
            'totalMinutesForDay' => $untrackedTotalMinutesForDay,
            'hoursForDay' => $untrackedHoursForDay,
            'minutesForDay' => $untrackedTotalMinutesForDay % 60
        ];
    }

}