<?php namespace App\Http\Controllers\API\Foods;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Menu\Entry;
use App\Models\Menu\Recipe;
use Auth;
use Illuminate\Http\Request;

/**
 * Class MenuEntriesController
 * @package App\Http\Controllers\Menu
 */
class MenuEntriesController extends Controller
{

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