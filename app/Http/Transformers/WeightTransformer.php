<?php namespace App\Http\Transformers;

use App\Models\Weights\Weight;
use League\Fractal\TransformerAbstract;

/**
 * Class WeightTransformer
 */
class WeightTransformer extends TransformerAbstract
{
    /**
     * @param Weight $weight
     * @return array
     */
    public function transform(Weight $weight)
    {
        return [
            'id' => $weight->id,
            'date' => $weight->date,
            'weight' => $weight->weight
        ];
    }

}