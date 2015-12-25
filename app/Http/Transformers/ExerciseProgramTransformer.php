<?php

namespace App\Http\Transformers;

use App\Models\Exercises\ExerciseProgram;
use League\Fractal\TransformerAbstract;

/**
 * Class ExerciseProgramTransformer
 */
class ExerciseProgramTransformer extends TransformerAbstract
{
    /**
     * @param ExerciseProgram $exerciseProgram
     * @return array
     */
    public function transform(ExerciseProgram $exerciseProgram)
    {
        $array = [
            'id' => $exerciseProgram->id,
            'name' => $exerciseProgram->name,
        ];

        return $array;
    }

}