<?php namespace App\Repositories\Weights;

use App\Models\Weights\Weight;

/**
 * Class WeightsRepository
 * @package App\Repositories\Weights
 */
class WeightsRepository
{

    public function insertOrUpdate($date, $weight)
    {
        if ($this->getWeight($date)) {
            //This date already has a weight entry. We are updating, not inserting.
            $this->updateWeight($date, $weight);
        }
        else {
            //we are inserting
            $this->insertWeight($date, $weight);
        }
        return $this->getWeight($date);
    }

    /**
     * select
     */
    public function getWeight($date)
    {
        $weight = Weight::where('date', $date)
            ->where('user_id', Auth::user()->id)
            ->pluck('weight');

        if (!$weight) {
            $weight = 0;
        }

        return $weight;
    }

    /**
     * insert
     */

    public function insertWeight($date, $weight)
    {
        return Weight::insert([
            'date' => $date,
            'weight' => $weight,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * update
     */
    public function updateWeight($date, $weight)
    {
        return Weight::where('date', $date)
              ->where('user_id', Auth::user()->id)
              ->update([
                'weight' => $weight
              ]);
    }

}