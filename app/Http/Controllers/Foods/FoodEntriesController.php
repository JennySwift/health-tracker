<?php namespace App\Http\Controllers\Foods;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

use App\Models\Foods\Entry;
use App\Models\Foods\Food;

/**
 * Class FoodEntriesController
 * @package App\Http\Controllers\Foods
 */
class FoodEntriesController extends Controller {

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
		$id = $request->get('id');
		$date = $request->get('date');
		Entry::where('id', $id)->delete();

		$response = array(
			"food_entries" => Entry::getFoodEntries($date),
			"calories_for_the_day" => number_format(Food::getCaloriesForDay($date), 2),
			"calories_for_the_week" => number_format(Food::getCaloriesFor7Days($date), 2)
		);
		return $response;
	}
}