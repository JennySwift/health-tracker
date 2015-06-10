<?php namespace App\Http\Controllers\Projects;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Projects\ProjectsRepository;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Projects\Timer;
use App\Models\Projects\Project;

class TimersController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProjectsRepository $projectsRepository)
    {
        $this->projectsRepository = $projectsRepository;
    }

    /**
     * Insert a new timer for a project.
     * Return all the projects,
     * as well as the project that is currently displaying in the project popup
     * @param Request $request
     * @return array
     */
    public function startProjectTimer(Request $request)
    {
        $project = Project::find($request->get('project_id'));
        Timer::create([
            'project_id' => $project->id,
            'start' => Carbon::now()->toDateTimeString()
        ]);

        return [
            'projects' => $this->projectsRepository->getProjects(),
            'project' => $this->projectsRepository->getProject($project->id)
        ];
    }

    /**
     * Stop the timer (update it).
     * Return all the projects,
     * as well as the project that is currently displaying in the project popup
     * @param Request $request
     * @return array
     */
    public function stopProjectTimer(Request $request)
    {
        $project = Project::find($request->get('project_id'));
        $last_timer_id = Timer::where('project_id', $project->id)->max('id');
        $timer = Timer::find($last_timer_id);
        $timer->finish = Carbon::now()->toDateTimeString();
        $timer->save();

        return [
            'projects' => $this->projectsRepository->getProjects(),
            'project' => $this->projectsRepository->getProject($project->id)
        ];
    }

    /**
     * Delete a timer
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request, $id)
    {
        $timer = Timer::find($id);

        if(is_null($timer)) {
            return response([
                'error' => 'Timer not found.',
                'status' => 404
            ], 404);
        }

        $timer->delete();

        // throw NotFoundException
        return response(null, 204);
    }

}
