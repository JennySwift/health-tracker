<?php namespace App\Http\Controllers\API\Weights;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\WeightsRepository;
use Auth;
use Debugbar;
use Illuminate\Http\Request;

/**
 * Class WeightsController
 * @package App\Http\Controllers\Weights
 */
class WeightsController extends Controller
{
    /**
     * @var WeightsRepository
     */
    private $weightsRepository;

    /**
     * @param WeightsRepository $weightsRepository
     */
    public function __construct(WeightsRepository $weightsRepository)
    {
        $this->weightsRepository = $weightsRepository;
    }
    /**
     * Get the user's weight for the day
     * @param $date
     * @return mixed
     */
    public function show($date)
    {
        return $this->weightsRepository->getWeightForTheDay($date);
    }

    /**
     * This method is a good example of the S.O.L.I.D principles
     * Todo: refactor into two separate methods
     * @param Request $request
     * @param WeightsRepository $weightsRepository
     */
    public function insertOrUpdateWeight(Request $request, WeightsRepository $weightsRepository)
    {
        $date = $request->get('date');
        $weight = $request->get('weight');

        return $weightsRepository->insertOrUpdate($date, $weight);

    }

}