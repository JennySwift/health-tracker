<?php namespace App\Http\Transformers;

use App\Models\Menu\Entry;
use League\Fractal\TransformerAbstract;

/**
 * Class MenuEntryTransformer
 */
class MenuEntryTransformer extends TransformerAbstract
{
    /**
     * @param Entry $entry
     * @return array
     */
    public function transform(Entry $entry)
    {
        $array = [
            'id' => $entry->id,
            'date' => $entry->date,
            'quantity' => $entry->quantity,
            'calories' => $entry->getCalories(),
            'food' => [
                'id' => $entry->food->id,
                'name' => $entry->food->name
            ],
            'unit' => [
                'id' => $entry->unit->id,
                'name' => $entry->unit->name
            ]
        ];

        if ($entry->recipe) {
            $array['recipe'] = [
                'id' => $entry->recipe->id,
                'name' => $entry->recipe->name
            ];
        }

        return $array;
    }

}