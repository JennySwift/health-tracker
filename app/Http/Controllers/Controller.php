<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	//So that I don't have to remember to uncomment line 18 of kernel.php before pushing

	// public function activateCsrfMiddleware()
	// {
	// 	if (App::environment() != 'local') {
	// 		$this->middleware('csrf');
	// 	}	
	// }

    /**
     * Create a 201 - Created response
     * @param Arrayable $data => Arrayable allows the method to accept any object with a toArray() method.
     * @return Response
     */
    public function responseCreated(Arrayable $data)
    {
        return response($data->toArray(), Response::HTTP_CREATED);
    }

    /**
     * Create a 200 - OK response
     * @param Arrayable $data
     * @return Response
     */
    public function responseOk(Arrayable $data)
    {
        return response($data->toArray(), Response::HTTP_OK);
    }

    /**
     * Create a 204 - No content response
     * @return Response
     */
    public function responseNoContent()
    {
        return response([], Response::HTTP_NO_CONTENT);
    }

}
