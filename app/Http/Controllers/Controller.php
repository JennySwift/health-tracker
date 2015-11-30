<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\TransformerAbstract;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {

        $this->request = $request;
    }

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

    /**
     * Create a 304 - No content response
     * @return Response
     */
    public function responseNotModified()
    {
        return response([], Response::HTTP_NOT_MODIFIED);
    }

    /**
     * @param Model               $model
     * @param TransformerAbstract $transformer
     * @param null                $key
     * @return Item
     */
//    public function createItem($model, TransformerAbstract $transformer, $key = null)
//    {
//        return new Item($model, $transformer, $key);
//    }

    /**
     * @param $resource
     * @return mixed
     */
//    public function responseWithTransformer($resource, $code)
//    {
//        $manager = new Manager();
//        $manager->setSerializer(new DataArraySerializer);
//
//        $manager->parseIncludes(request()->get('includes', []));
//
//        return response()->json(
//            $manager->createData($resource)->toArray(),
//            $code
//        );
//    }

    /**
     * Return response ok code with transformed resource
     * @param $resource
     * @return mixed
     */
    public function responseOkWithTransformer($resource, $transformer)
    {
        //Transform
        $resource = createItem($resource, $transformer);

        return response(transform($resource), Response::HTTP_OK);
    }

    /**
     * Return response created code with transformed resource
     * @param $resource
     * @param $transformer
     * @return mixed
     */
    public function responseCreatedWithTransformer($resource, $transformer)
    {
        //Transform
        $resource = createItem($resource, $transformer);

        return response(transform($resource), Response::HTTP_CREATED);
    }

    /**
     * For Fractal transformer
     * @param $resource
     * @return array
     */
    public function transform($resource)
    {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer);

//        $manager->parseIncludes(request()->get('includes', []));

        if ($this->request->has('include')) {
//            $manager->parseIncludes($_GET['include']);
            //testable :) $_GET is not object-oriented
            //download latest version of airmail :)
            //pull request to laravel-starter repository getting rid of things in .gitignore
            // /public/build
            // /public/css
            // /public/js
            $manager->parseIncludes($this->request->include);
        }

        return $manager->createData($resource)->toArray();
    }

    /**
     * For Fractal transformer
     * @param $model
     * @param TransformerAbstract $transformer
     * @param null $key
     * @return Collection
     */
    public function createCollection($model, TransformerAbstract $transformer, $key = null)
    {
        return new Collection($model, $transformer, $key);
    }


}
