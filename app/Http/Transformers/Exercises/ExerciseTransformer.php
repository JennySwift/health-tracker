<?php namespace App\Http\Transformers\Exercises;

use App\Http\Transformers\TagTransformer;
use App\Http\Transformers\UnitTransformer;
use App\Models\Exercises\Exercise;
use League\Fractal\TransformerAbstract;

/**
 * Class ExerciseTransformer
 */
class ExerciseTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['tags', 'defaultUnit'];

    /**
     * @var array
     */
    private $params;

    /**
     * ExerciseTransformer constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->params = $params;
    }

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
            'tag_ids' => $exercise->tags()->lists('id'),
            'target' => $exercise->target,
            'priority' => $exercise->priority,
            'program' => $exercise->program,
            'lastDone' => $exercise->lastDone,
            'stretch' => $exercise->stretch,
            'frequency' => $exercise->frequency
        ];

        if ($exercise->series) {
            $array['series'] = [
                'id' => $exercise->series->id,
                'name' => $exercise->series->name
            ];
        }

        return $array;
    }

    /**
     *
     * @param Exercise $exercise
     * @return \League\Fractal\Resource\Item
     */
    public function includeDefaultUnit(Exercise $exercise)
    {
        if ($exercise->defaultUnit) {
            return createItem($exercise->defaultUnit, new UnitTransformer);
        }
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