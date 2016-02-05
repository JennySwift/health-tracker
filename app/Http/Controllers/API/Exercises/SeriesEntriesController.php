<?php namespace App\Http\Controllers\API\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\Exercises\ExerciseEntryTransformer;
use App\Models\Exercises\Series;
use App\Repositories\ExerciseSeriesRepository;
use Illuminate\Http\Request;

/**
 * Class SeriesEntriesController
 * @package App\Http\Controllers\Exercises
 */
class SeriesEntriesController extends Controller
{
    /**
     * @var ExerciseSeriesRepository
     */
    private $exerciseSeriesRepository;

    /**
     * @param ExerciseSeriesRepository $exerciseSeriesRepository
     */
    public function __construct(ExerciseSeriesRepository $exerciseSeriesRepository)
    {
        $this->exerciseSeriesRepository = $exerciseSeriesRepository;
    }

    /**
     * Get all the user's entries for an exercise series.
     * This could be expressed two ways:
     * 1: $series->entries
     * 2: $entry->where('series_id', $series->id)
     * @param Request $request
     * @return array
     */
    public function show($series_id)
    {
        //Fetch the series (singular-the series that was clicked on)
        $series = Series::find($series_id);

        return transform(createCollection(
            $this->exerciseSeriesRepository->getExerciseSeriesHistory($series),
            new ExerciseEntryTransformer
        ))['data'];
    }
}
