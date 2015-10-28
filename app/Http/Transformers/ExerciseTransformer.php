<?php namespace App\Http\Transformers;

use App\Models\Exercises\Exercise;
use League\Fractal\TransformerAbstract;

/**
 * Class ExerciseTransformer
 */
class ExerciseTransformer extends TransformerAbstract
{
    /**
     * @param Exercise $exercise
     * @return array
     */
    public function transform(Exercise $exercise)
    {
        $array = [
            'id' => $exercise->id,
            'name' => $exercise->name,
            'stepNumber' => $exercise->step_number,
            'series' => [
                'id' => $exercise->series->id,
                'name' => $exercise->series->name
            ],
            'defaultQuantity' => $exercise->default_quantity,
            'defaultUnit' => [
                'id' => $exercise->defaultUnit->id,
                'name' => $exercise->defaultUnit->name
            ],
            'tag_ids' => $exercise->tags()->lists('id')
        ];

        return $array;
    }

}