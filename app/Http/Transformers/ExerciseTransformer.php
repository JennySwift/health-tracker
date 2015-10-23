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
//        $array = [
//            'id' => $exercise->id,
//            'name' => $exercise->name,
//            'path' => $exercise->path,
//            'stepNumer' => $exercise,
//            'series_id' => $exercise,
//            'description' => $exercise,
//            'default_unit_id' => $exercise,
//            'default_unit_name' => $exercise,
//            'series_name' => $exercise,
//            'defaultQuantity' => $exercise,
//            'defaultCalories' => $exercise,
//            'tags' => $exercise,
//        ];

//        if ($exercise->default_unit_id) {
//            $array['defaultUnit'] = [
//                'id' => $food->defaultUnit->id,
//                'name' => $food->defaultUnit->name
//            ];
//        }
//        return $array;
    }

}