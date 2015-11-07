<?php

namespace App\Http\Transformers;

use App\Models\Exercises\Workout;
use League\Fractal\TransformerAbstract;

/**
 * Class WorkoutTransformer
 */
class WorkoutTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(Workout $workout)
    {
        $array = [
            'id' => $workout->id,
            'name' => $workout->name,
        ];

        return $array;
    }

}