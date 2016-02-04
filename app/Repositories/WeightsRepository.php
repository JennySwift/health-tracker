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
}