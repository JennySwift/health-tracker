<?php namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\TagTransformer;
use App\Models\Tags\Tag;
use App\Repositories\RecipeTagsRepository;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;

/**
 * Class RecipeTagsController
 * @VP:
 * Again, duplicate code here from ExerciseTagsController,
 * in order to have an index method for both exercise tags and recipe tags.
 * Good idea or bad idea?
 * @package App\Http\Controllers\Tags
 */
class RecipeTagsController extends Controller
{
    /**
     * @var RecipeTagsRepository
     */
    private $recipeTagsRepository;

    /**
     * @param RecipeTagsRepository $recipeTagsRepository
     */
    public function __construct(RecipeTagsRepository $recipeTagsRepository)
    {
        $this->recipeTagsRepository = $recipeTagsRepository;
    }

    /**
     *
     * @return mixed
     */
    public function index()
    {
        return $this->recipeTagsRepository->getrecipeTags();
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
            'for' => 'recipe'
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