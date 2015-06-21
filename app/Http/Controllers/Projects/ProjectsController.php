<?php namespace App\Http\Controllers\Projects;

use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Projects\Payee;
use App\Models\Projects\Payer;
use App\Repositories\Projects\ProjectsRepository;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use JavaScript;
use Auth;
use Carbon\Carbon;

use App\Models\Projects\Project;

/**
 * Class ProjectsController
 * @package App\Http\Controllers\Projects
 */
class ProjectsController extends Controller {

    /**
     * @var ProjectsRepository
     */
    protected $projectsRepository;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(ProjectsRepository $projectsRepository)
	{
		$this->middleware('auth');
		$this->projectsRepository = $projectsRepository;
	}

    public function show($id)
    {
        $project = Project::find($id);
        //This return the first project in the database, not the project with the $id.
//        return $project->with('timers')->first();

        return Project::where('id', $id)
            ->with('timers')
            ->first();
    }

    /**
     * Insert a new project
     * Return projects
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $payer_email = $request->get('payer_email');
        $description = $request->get('description');
        $rate = $request->get('rate');

        $this->projectsRepository->createProject($payer_email, $description, $rate);

        $payee = Payee::find(Auth::user()->id);
        return $payee->projects;
    }

    /**
     * Delete a project (only when user is the payee)
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
//        try{
        /**
         * @see http://laravel.com/docs/5.0/errors#handling-errors
         */
            $project = Project::whereUserIsPayee()->findOrFail($id);
//        }
//        catch(ModelNotFoundException $e) {
//            return response([
//                'error' => 'Model not found.',
//                'status' => Response::HTTP_NOT_FOUND
//            ], Response::HTTP_NOT_FOUND);
//        }

//        if(is_null($project)) {
//            return response([
//                'error' => 'Project not found.',
//                'status' => 404
//            ], 404);
//        }

        $project->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
