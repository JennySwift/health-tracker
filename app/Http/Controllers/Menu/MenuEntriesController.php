<?php namespace App\Http\Controllers\Foods;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Foods\Entry;
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