<?php namespace App\Http\Controllers\Weights;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Weights\WeightsRepository;
use Illuminate\Http\Request;
use Auth;
use Debugbar;

use App\Models\Weights\Weight;

class WeightsController extends Controller {

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
