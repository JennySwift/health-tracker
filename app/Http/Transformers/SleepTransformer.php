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
        $array = [
            'id' => $sleep->id,
            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $sleep->start)->format('g:ia'),
            'finish' => Carbon::createFromFormat('Y-m-d H:i:s', $sleep->finish)->format('g:ia'),
        ];

        return $array;
    }

}