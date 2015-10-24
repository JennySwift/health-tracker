<?php namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Menu\Entry;
use App\Models\Menu\Recipe;
use App\Repositories\MenuEntriesRepository;
use Auth;
use Illuminate\Http\Request;

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
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $entry = new Entry([
            'date' => $request->get('date'),
            'food_id' => $request->get('food_id'),
            'quantity' => $request->get('quantity'),
            'unit_id' => $request->get('unit_id'),
        ]);

        if ($request->get('recipe')) {
            //We are inserting a recipe
            $recipe = Recipe::find($request->get('recipe_id'));
            $entry->recipe()->associate($recipe);
        }

        $entry->user()->associate(Auth::user());
        $entry->save();
        return $this->responseCreated($entry);

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