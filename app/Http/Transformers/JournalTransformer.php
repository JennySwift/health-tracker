<?php namespace App\Http\Transformers;

use App\Models\Journal\Journal;
use League\Fractal\TransformerAbstract;

/**
 * Class JournalTransformer
 */
class JournalTransformer extends TransformerAbstract
{
    /**
     * @param Journal $journal
     * @return array
     */
    public function transform(Journal $journal)
    {
        return [
            'id' => $journal->id,
            'date' => $journal->date,
            'text' => $journal->text
        ];
    }

}