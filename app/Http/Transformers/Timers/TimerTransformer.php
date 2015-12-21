<?php

namespace App\Http\Transformers\Timers;

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
     * @var array
     */
    private $params;

    /**
     * TimerTransformer constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * @param Timer $timer
     * @return array
     */
    public function transform(Timer $timer)
    {
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $timer->start);

        $array = [
            'id' => $timer->id,
            'start' => $timer->start,
            'formattedStart' => $timer->formattedStart,
            'startDate' => $start->format('d/m/y')
        ];

        if ($timer->finish) {
            $array['formattedFinish'] = Carbon::createFromFormat('Y-m-d H:i:s', $timer->finish)->format('g:ia');
            $array['hours'] = $timer->hours;
            $array['minutes'] = $timer->minutes;
            $array['formattedMinutes'] = $timer->formattedMinutes;
            $array['durationInMinutes'] = $timer->totalMinutes;
            if (isset($this->params['date'])) {
                $array['durationInMinutesForDay'] = $timer->getTotalMinutesForDay(Carbon::createFromFormat('Y-m-d', $this->params['date']));
            }
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