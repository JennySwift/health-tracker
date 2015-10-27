<?php namespace App\Http\Controllers\API\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\TagTransformer;
use App\Models\Tags\Tag;
use App\Repositories\ExerciseTagsRepository;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;

/**
 * Class ExerciseTagsController
 * @package App\Http\Controllers\Tags
 */
class ExerciseTagsController extends Controller
{
    /**
     * @var ExerciseTagsRepository
     */
    private $exerciseTagsRepository;

    /**
     * @param ExerciseTagsRepository $exerciseTagsRepository
     */
    public function __construct(ExerciseTagsRepository $exerciseTagsRepository)
    {
        $this->exerciseTagsRepository = $exerciseTagsRepository;
    }

    /**
     *
     * @return mixed
     */
    public function index()
    {
        return $this->exerciseTagsRepository->getExerciseTags();
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tag = new Tag([
            'name' => $request->get('name'),
            'for' => $request->get('for')
        ]);

        $tag->user()->associate(Auth::user());
        $tag->save();

        return $this->responseCreatedWithTransformer($tag, new TagTransformer);
	}

    /**
     *
     * @param Tag $tag
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return $this->responseNoContent();
    }
}