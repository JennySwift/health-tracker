<?php namespace App\Http\Controllers\Projects;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Projects\ProjectsRepository;

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

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request)
	{
		$id = $request->get('id');

		Project::destroy($id);

		$projects = [
			'payee' => $this->projectsRepository->getProjectsAsPayee(),
			'payer' => $this->projectsRepository->getProjectsAsPayer()
		];

		return response()->json($projects);
	}

	
}
