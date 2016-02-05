<?php

namespace App\Http\Transformers\Exercises;

use App\Models\Exercises\Entry;
use League\Fractal\TransformerAbstract;

/**
 * Class ExerciseEntryTransformer
 */
class ExerciseEntryTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['exercise'];

    /**
     * @VP:
     * When I include the exercise like this, there's heaps of data
     * I don't need in the response
     * (for example I don't need the program of the exercise),
     * whereas if I do just what I need
     * with 'exercise' => [...],
     * it seems inconsistent with my other responses,
     * and therefore more fiddly to test.
     * @param Entry $entry
     * @return \League\Fractal\Resource\Collection
     */
    public function includeExercise(Entry $entry)
    {
        return $this->item($entry->exercise, new ExerciseTransformer);
    }

    /**
     * @param Entry $entry
     * @return array
     */
    public function transform(Entry $entry)
    {
        $array = [
            'id' => $entry->id,
            'date' => $entry->date,
            'daysAgo' => $entry->days_ago,
            'unit' => [
                'id' => $entry->unit->id,
                'name' => $entry->unit->name
            ],
            'sets' => $entry->sets,
            'total' => $entry->total,
            'quantity' => $entry->quantity,
            'createdAt' => $entry->created_at->format('h:ia')
        ];

        return $array;
    }

}