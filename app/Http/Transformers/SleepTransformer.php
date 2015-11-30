<?php

namespace App\Http\Transformers;

use App\Models\Sleep\Sleep;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * Class SleepTransformer
 */
class SleepTransformer extends TransformerAbstract
{
    /**
     * @param Sleep $sleep
     * @return array
     */
    public function transform(Sleep $sleep)
    {
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $sleep->start);
        $finish = Carbon::createFromFormat('Y-m-d H:i:s', $sleep->finish);
        $minutes = $finish->diffInMinutes($start);
        $minutes = $minutes % 60;
        $hours = $finish->diffInHours($start);

        $array = [
            'id' => $sleep->id,
            'start' => $start->format('g:ia'),
            'finish' => $finish->format('g:ia'),
            'startDate' => $start->format('d/m/y'),
            'hours' => $hours,
            'minutes' => $minutes
        ];

        return $array;
    }

}