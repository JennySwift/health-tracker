<?php namespace App\Http\Controllers\API\Weights;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\WeightTransformer;
use App\Models\Weights\Weight;
use App\Repositories\WeightsRepository;
use Auth;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     * If the user does not have a weight entry for the day,
     * all fields will be null
     * GET /api/weights/{weights}
     * @param Weight $weight
     * @return Response
     */
    public function show(Weight $weight)
    {
        if ($weight) {
            $weight = $this->transform($this->createItem($weight, new WeightTransformer))['data'];
            return response($weight, Response::HTTP_OK);
        }
    }

    /**
     * POST /api/weights
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $weight = new Weight($request->only([
            'weight',
            'date'
        ]));
        $weight->user()->associate(Auth::user());
        $weight->save();

        $weight = $this->transform($this->createItem($weight, new WeightTransformer))['data'];
        return response($weight, Response::HTTP_CREATED);
    }

    /**
    * UPDATE /api/weights/{weights}
    * @param Request $request
    * @param Weight $weight
    * @return Response
    */
    public function update(Request $request, Weight $weight)
    {
        // Create an array with the new fields merged
        $data = array_compare($weight->toArray(), $request->only([
            'weight'
        ]));

        $weight->update($data);

        $weight = $this->transform($this->createItem($weight, new WeightTransformer))['data'];
        return response($weight, Response::HTTP_OK);
    }
}