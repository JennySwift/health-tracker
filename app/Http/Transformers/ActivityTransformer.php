<?php

namespace App\Http\Transformers;

use App\Models\Timers\Activity;
use League\Fractal\TransformerAbstract;

/**
 * Class ActivityTransformer
 */
class ActivityTransformer extends TransformerAbstract
{
    /**
     * @param Activity $activity
     * @return array
     */
    public function transform(Activity $activity)
    {
        $totalDuration = 0;
        foreach ($activity->timers as $timer) {
            $totalDuration+= $timer->durationInMinutes;
        }
        
        $array = [
            'id' => $activity->id,
            'name' => $activity->name,
            'totalDuration' => $totalDuration
        ];

        return $array;
    }

}