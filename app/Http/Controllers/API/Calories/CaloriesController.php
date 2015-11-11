<?php namespace App\Http\Controllers\API\Calories;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\CaloriesRepository;
use Auth;
use Debugbar;
use Illuminate\Http\Request;

/**
 * Class CaloriesController
 * @package App\Http\Controllers\Weights
 */
class CaloriesController extends Controller
{
    /**
     * @var CaloriesRepository
     */
    private $caloriesRepository;

    /**
     * @param CaloriesRepository $caloriesRepository
     */
    public function __construct(CaloriesRepository $caloriesRepository)
    {
        $this->caloriesRepository = $caloriesRepository;
    }
    /**
     * Get the user's total calories for the day
     * @param $date
     * @return mixed
     */
    public function show($date)
    {
        return [
            'forTheDay' => $this->caloriesRepository->getCaloriesForDay($date),
            'averageFor7Days' => $this->caloriesRepository->getCaloriesFor7Days($date)
        ];
    }

}