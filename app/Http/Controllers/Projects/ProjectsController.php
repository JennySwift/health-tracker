<?php namespace App\Http\Controllers\Projects;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Projects\ProjectsRepository;

use Illuminate\Http\Request;
use JavaScript;
use Auth;
use Carbon\Carbon;

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
			'projects' => [
				'payee' => $this->projectsRepository->getProjectsAsPayee(),
				'payer' => $this->projectsRepository->getProjectsAsPayer()
			]
		]);

		// return $this->getPayeeTimers();
		return view('timers');
	}

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

	
}
