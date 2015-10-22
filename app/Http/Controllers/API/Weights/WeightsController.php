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
     * This method is a good example of the S.O.L.I.D principles
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