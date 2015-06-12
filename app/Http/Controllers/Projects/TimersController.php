<?php namespace App\Http\Controllers\Projects;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Projects\ProjectsRepository;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Debugbar;

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
            'projects' => $this->projectsRepository->getProjectsResponseForCurrentUser(),
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

        //Calculate price
        //Note for developing-price will be zero if time is less than 30 seconds
        $time = $this->calculateTimerTime($timer->start, $timer->finish);
        
        $rate = $timer->project->rate_per_hour;
        $timer->price = $this->getTimerPrice($time, $rate);

        $timer->save();

        return [
            'projects' => $this->projectsRepository->getProjectsResponseForCurrentUser(),
            'project' => $this->projectsRepository->getProject($project->id)
        ];
    }

    private function calculateTimerTime($start, $finish)
    {
        $carbon_start = Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $carbon_finish = Carbon::createFromFormat('Y-m-d H:i:s', $finish);
        $time = $carbon_finish->diff($carbon_start);
        return $time;
    }

    private function getTimerPrice($time, $rate)
    {
        $price = 0;

        if ($time->s > 30) {
            $time->i = $time->i + 1;
        }
        $price+= $rate * $time->h;
        $price+= $rate / 60 * $time->i;
        return $price;
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
