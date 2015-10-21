<?php namespace App\Http\Controllers\Foods;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Foods\Entry;
use App\Models\Foods\Food;
use Auth;
use Illuminate\Http\Request;

/**
 * Class FoodEntriesController
 * @package App\Http\Controllers\Foods
 */
class FoodEntriesController extends Controller
{

    /**
     *
     * @param Request $request
     * @return array
     */
    public function insertMenuEntry(Request $request)
    {
        $data = $request->all();
        $date = $data['date'];
        Entry::insertMenuEntry($data);

        $response = array(
            "food_entries" => Entry::getFoodEntries($date),
            "calories_for_the_day" => number_format(Food::getCaloriesForDay($date), 2),
            "calories_for_the_week" => number_format(Food::getCaloriesFor7Days($date), 2),
        );

        return $response;
    }

    /**
     *
     * @param Request $request
     * @return array
     */
    public function deleteFoodEntry(Request $request)
    {
        $date = $request->get('date');
        $entry = Entry::forCurrentUser()->findOrFail($request->get('id'));
        $entry->delete();

        $response = array(
            "food_entries" => Entry::getFoodEntries($date),
            "calories_for_the_day" => number_format(Food::getCaloriesForDay($date), 2),
            "calories_for_the_week" => number_format(Food::getCaloriesFor7Days($date), 2)
        );

        return $response;
	}
}