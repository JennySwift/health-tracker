<?php namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\Menu\MenuEntryTransformer;
use App\Models\Menu\Entry;
use App\Models\Menu\Food;
use App\Models\Menu\Recipe;
use App\Models\Units\Unit;
use App\Repositories\MenuEntriesRepository;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class MenuEntriesController
 * @package App\Http\Controllers\Menu
 */
class MenuEntriesController extends Controller
{
    /**
     * @var MenuEntriesRepository
     */
    private $menuEntriesRepository;

    /**
     * @param MenuEntriesRepository $menuEntriesRepository
     */
    public function __construct(MenuEntriesRepository $menuEntriesRepository)
    {
        $this->menuEntriesRepository = $menuEntriesRepository;
    }

    /**
     * Get the user's menu (food, recipe) entries for the day
     * @param $date
     * @return mixed
     */
    public function index($date)
    {
        return $this->menuEntriesRepository->getEntriesForTheDay($date);
    }

    /**
     * Entry can be either just a food, or part of a recipe.
     * When part of a recipe, the store method inserts just one food at a time,
     * so that the store method is RESTful.
     * So lots of ajax requests will be made to insert
     * all the entries for a whole recipe.
     * POST /api/menuEntries
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $entry = new Entry($request->only([
            'date', 'quantity'
        ]));
        $entry->user()->associate(Auth::user());

        $entry->food()->associate(Food::find($request->get('food_id')));

        if ($request->get('recipe_id')) {
            $entry->recipe()->associate(Recipe::find($request->get('recipe_id')));
        }

        $entry->unit()->associate(Unit::find($request->get('unit_id')));
        $entry->save();

        $entry = $this->transform($this->createItem($entry, new MenuEntryTransformer))['data'];
        return response($entry, Response::HTTP_CREATED);
    }

    /**
     *
     * @param Entry $entry
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Entry $entry)
    {
        $entry->delete();
        return $this->responseNoContent();
    }
}