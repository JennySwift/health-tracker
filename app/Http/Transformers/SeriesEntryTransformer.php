<?php

namespace App\Http\Transformers;

use App\Models\Exercises\Entry;
use League\Fractal\TransformerAbstract;

/**
 * Class SeriesEntryTransformer
 */
class SeriesEntryTransformer extends TransformerAbstract
{
    /**
     * @param Entry $entry
     * @return array
     */
    public function transform(Entry $entry)
    {
        $array = [
            'date' => $entry->date,
            'days_ago' => $entry->days_ago,
            'exercise' => [
                'id' => $entry->exercise_id,
                'name' => $entry->exercise->name,
                'description' => $entry->exercise->description,
                'step_number' => $entry->exercise->step_number,
            ],
            'unit' => [
                'id' => $entry->unit->id,
                'name' => $entry->unit->name
            ],
            'default_unit_id' => $entry->exercise->default_unit_id,
            'sets' => $entry->sets,
            'total' => $entry->total,
            'quantity' => $entry->quantity,
        ];

        return $array;
    }

}