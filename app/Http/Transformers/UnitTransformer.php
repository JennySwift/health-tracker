<?php namespace App\Http\Transformers;

use App\Models\Units\Unit;
use League\Fractal\TransformerAbstract;

/**
 * Class UnitTransformer
 */
class UnitTransformer extends TransformerAbstract
{
    /**
     * Transform unit response
     * @param Unit $unit
     * @return array
     */
    public function transform(Unit $unit)
    {
        return [
            'id' => $unit->id,
            'name' => $unit->name,
            'for' => $unit->for
        ];
    }

}