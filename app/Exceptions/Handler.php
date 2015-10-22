<?php namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Redirect;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        'Symfony\Component\HttpKernel\Exception\HttpException'
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        // Throttle package exception handler
        if ($e instanceof TooManyRequestsHttpException) {
            return Redirect::back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Too many failed login attempts!',
                ]);
        }

//        if ($e instanceof NotLoggedInException) {
//            return response([
//                'error' => "Not logged in.",
//                'status' => Response::HTTP_UNAUTHORIZED
//            ], Response::HTTP_UNAUTHORIZED);
//        }

//        if ($e instanceof ModelAlreadyExistsException) {
//            return response([
//                'error' => "{$e->model} already exists.",
//                'status' => Response::HTTP_BAD_REQUEST
//            ], Response::HTTP_BAD_REQUEST);
//        }

        if ($e instanceof \InvalidArgumentException) {
            return response([
                'error' => $e->getMessage(),
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }

        // Model not found exception handler (app-wide)
        if ($e instanceof ModelNotFoundException) {

            // Build a "fake" instance of the model which was not found
            // and fetch the shortname of the class
            // Ex.: If we have a App\Models\Projects\Project model
            // Then we would return Project
            $model = (new \ReflectionClass($e->getModel()))->getShortName();

            return response([
                'error' => "{$model} not found.",
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }

        return parent::render($request, $e);
    }

}
