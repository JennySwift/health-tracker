<?php

namespace App\Http\Transformers;

use App\Models\Sleep\Sleep;
use App\Models\Timers\Timer;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * Class TimerTransformer
 */
class TimerTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'activity'
    ];

    /**
     * @param Timer $timer
     * @return array
     */
    public function transform(Timer $timer)
    {
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $timer->start);

        $array = [
            'id' => $timer->id,
            'start' => $start->format('g:ia'),
            'startDate' => $start->format('d/m/y')
        ];

        if ($timer->finish) {
            $finish = Carbon::createFromFormat('Y-m-d H:i:s', $timer->finish);
            $durationInMinutes = $finish->diffInMinutes($start);
            $minutes = $durationInMinutes % 60;
            $hours = $finish->diffInHours($start);

            $array['finish'] = $finish->format('g:ia');
            $array['hours'] = $hours;
            $array['minutes'] = $minutes;
            $array['durationInMinutes'] = $durationInMinutes;
        }

        return $array;
    }

    /**
     *
     * @param Timer $timer
     * @return \League\Fractal\Resource\Item
     */
    public function includeActivity(Timer $timer)
    {
        $activity = $timer->activity;

        return $this->item($activity, new ActivityTransformer);
    }

}