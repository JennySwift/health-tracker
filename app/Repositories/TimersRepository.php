<?php

namespace App\Repositories;


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


}