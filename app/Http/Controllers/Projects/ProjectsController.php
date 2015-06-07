<?php namespace App\Http\Controllers\Projects;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Projects\ProjectsRepository;

use App\User;
use Illuminate\Http\Request;
use JavaScript;
use Auth;
use Carbon\Carbon;

use App\Models\Projects\Project;

class ProjectsController extends Controller {

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

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        JavaScript::put([
            'projects' => $this->projectsRepository->getProjects(),
            'payers' => $this->projectsRepository->getPayers(),
            'payees' => $this->projectsRepository->getPayees()
        ]);

//        return $this->projectsRepository->getPayers();
//		return $this->projectsRepository->getProjects();
        return view('projects');
    }

    /**
     * select
     */


    /**
     * insert
     */

    /**
     * Add a new payer for the user (payee)
     * so that the user can create a project with that person as payer
     * @param Request $request
     * @return mixed
     */
    public function addPayer(Request $request)
    {
        $payer_email = $request->get('payer_email');
        $user = User::find(Auth::user()->id);
        $this->projectsRepository->addPayer($payer_email);
        return $user->payers;
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

        $projects = [
            'payee' => $this->projectsRepository->getProjectsAsPayee(),
            'payer' => $this->projectsRepository->getProjectsAsPayer()
        ];

        return response()->json($projects);
    }

    /**
     * update
     */

    /**
     * delete
     */

    /**
     * Delete a project
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request, $id)
    {
//		Project::destroy($id);

        $project = Project::find($id);

        if(is_null($project)) {
            return response([
                'error' => 'Project not found.',
                'status' => 404
            ], 404);
        }

        $project->delete();

        // throw NotFoundException
        return response(null, 204);

//		$projects = [
//			'payee' => $this->projectsRepository->getProjectsAsPayee(),
//			'payer' => $this->projectsRepository->getProjectsAsPayer()
//		];
//
//		return response()->json($projects);
    }
}
