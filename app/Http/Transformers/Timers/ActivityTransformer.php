<?php

namespace App\Http\Transformers\Timers;

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
        $totalMinutes = 0;
        foreach ($activity->timers as $timer) {
            if ($timer->finish) {
                $totalMinutes+= $timer->totalMinutes;
            }
        }
        
        $array = [
            'id' => $activity->id,
            'name' => $activity->name,
            'totalMinutes' => $totalMinutes,
            'color' => $activity->color,
        ];

        return $array;
    }

}