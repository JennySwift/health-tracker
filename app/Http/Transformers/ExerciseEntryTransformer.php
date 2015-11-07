<?php

namespace App\Http\Transformers;

use App\Models\Exercises\Entry;
use League\Fractal\TransformerAbstract;

/**
 * Class ExerciseEntryTransformer
 */
class ExerciseEntryTransformer extends TransformerAbstract
{
    /**
     * @param Entry $entry
     * @return array
     */
    public function transform(Entry $entry)
    {
        $array = [
            'exercise' => [
                'id' => $entry->exercise_id,
                'name' => $entry->exercise->name,
                'description' => $entry->exercise->description,
                'step_number' => $entry->exercise->step_number,
                'default_unit_id' => $entry->exercise->default_unit_id,
            ],
            'unit' => [
                'id' => $entry->unit->id,
                'name' => $entry->unit->name
            ],
            'sets' => $entry->sets,
            'total' => $entry->total,
            'quantity' => $entry->quantity,
        ];

        return $array;
    }

}