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
                'stepNumber' => $entry->exercise->step_number,
                'defaultQuantity' => $entry->exercise->default_quantity,
            ],
            'unit' => [
                'id' => $entry->unit->id,
                'name' => $entry->unit->name
            ],
            'sets' => $entry->sets,
            'total' => $entry->total,
            'quantity' => $entry->quantity,
        ];

        if ($entry->exercise->defaultUnit) {
            $array['exercise']['defaultUnit'] = [
                'id' => $entry->exercise->defaultUnit->id,
                'name' => $entry->exercise->defaultUnit->name
            ];
        }

        return $array;
    }

}