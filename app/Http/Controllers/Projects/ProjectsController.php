<?php namespace App\Http\Controllers\Projects;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Projects\Payee;
use App\Models\Projects\Payer;
use App\Repositories\Projects\ProjectsRepository;

use App\User;
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
        $project = Project::whereUserIsPayee()->find($id);

        if(is_null($project)) {
            return response([
                'error' => 'Project not found.',
                'status' => 404
            ], 404);
        }

        $project->delete();

        return response(null, 204);
    }
}
