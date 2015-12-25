<?php

namespace App\Http\Transformers;

use App\Models\Exercises\Series;
use League\Fractal\TransformerAbstract;

/**
 * Class SeriesTransformer
 */
class SeriesTransformer extends TransformerAbstract
{
    /**
     * Todo: This isn't needed all the time
     * @var array
     */
    protected $defaultIncludes = ['workouts'];

    /**
     * @return array
     */
    public function transform(Series $series)
    {
        $array = [
            'id' => $series->id,
            'name' => $series->name,
            'priority' => $series->priority,
            'workout_ids' => $series->workouts()->lists('id'),
        ];

        return $array;
    }

    /**
     *
     * @param Series $series
     * @return \League\Fractal\Resource\Collection
     */
    public function includeWorkouts(Series $series)
    {
        return createCollection($series->workouts, new WorkoutTransformer);
    }

}