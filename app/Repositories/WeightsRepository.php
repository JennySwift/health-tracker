<?php namespace App\Repositories;

use App\Http\Transformers\WeightTransformer;
use App\Models\Weights\Weight;
use Auth;

/**
 * Class WeightsRepository
 * @package App\Repositories\Weights
 */
class WeightsRepository
{

    /**
     *
     * @param $date
     * @param $weight
     * @return int
     */
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
     *
     * @param $date
     * @return int
     */
    public function getWeightForTheDay($date)
    {
        $weight = Weight::forCurrentUser()
            ->where('date', $date)
            ->first();

        if ($weight) {
            return transform(createItem($weight, new WeightTransformer))['data'];
        }

        return null;
    }

    /**
     *
     * @param $date
     * @param $weight
     * @return mixed
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
     *
     * @param $date
     * @param $weight
     * @return mixed
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