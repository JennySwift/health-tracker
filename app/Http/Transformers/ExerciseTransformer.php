<?php namespace App\Http\Transformers;

use App\Models\Exercises\Exercise;
use League\Fractal\TransformerAbstract;

/**
 * Class ExerciseTransformer
 */
class ExerciseTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['tags'];

    /**
     * @param Exercise $exercise
     * @return array
     */
    public function transform(Exercise $exercise)
    {
        $array = [
            'id' => $exercise->id,
            'name' => $exercise->name,
            'description' => $exercise->description,
            'stepNumber' => $exercise->step_number,
            'defaultQuantity' => $exercise->default_quantity,
            'tag_ids' => $exercise->tags()->lists('id')
        ];

        if ($exercise->series) {
            $array['series'] = [
                'id' => $exercise->series->id,
                'name' => $exercise->series->name
            ];
        }

        if ($exercise->defaultUnit) {
            $array['defaultUnit'] = [
                'id' => $exercise->defaultUnit->id,
                'name' => $exercise->defaultUnit->name
            ];
        }

        return $array;
    }

    /**
     *
     * @param Exercise $exercise
     * @return \League\Fractal\Resource\Collection
     */
    public function includeTags(Exercise $exercise)
    {
        $tags = $exercise->tags;

        return createCollection($tags, new TagTransformer);
    }

}