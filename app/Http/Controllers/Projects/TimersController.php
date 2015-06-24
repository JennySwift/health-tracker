<?php namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Projects\Payee;
use App\Models\Projects\Payer;
use App\Models\Projects\Project;
use App\Models\Projects\Timer;
use App\Repositories\Projects\ProjectsRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pusher;

/**
 * Class TimersController
 * @package App\Http\Controllers\Projects
 */
class TimersController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @param ProjectsRepository $projectsRepository
     */
    public function __construct(ProjectsRepository $projectsRepository)
    {
        $this->projectsRepository = $projectsRepository;
    }

    /**
     * Mark all timers that belong to the user (payee),
     * and are with a certain payer, as paid
     *
     * WARNING: Be careful, method not Restful!
     *
     * @param Request $request
     */
    public function markAsPaid(Request $request)
    {
        $payer = Payer::findOrFail($request->get('payer_id'));
        $payee = Payee::find(Auth::user()->id);

        $project_ids = $payee->projectsAsPayee()
                             ->where('payer_id', $payer->id)
                             ->lists('id');

        /**
         * @VP:
         * Is this vulnerable to mass assignment? How would I fix that?
         *
         * You could filter the timers by projects using the relationship
         * ->with(['projects' => function($query) use ($project_ids){
         *     return $query->whereIn('id', $project_ids);
         * }])
         */
        Timer::whereIn('project_id', $project_ids)
             ->where('paid', 0)
             ->update([
                'paid' => 1,
                'time_of_payment' => Carbon::now()
             ]);

        // @TODO return something useful here. Currently a page refresh is required to see the changes.
        // @TODO Return collection of timers that have been modified
    }

    /**
     * Insert a new timer for a project.
     * Return all the projects,
     * as well as the project that is currently displaying in the project popup
     *
     * WARNING: Be careful, method not Restful! Should be a POST request to /projects/{project}/timers
     * and return the timer newly created :) (So it should be in a ProjectTimersController,
     * store method, not the TimersController). You could also use Model Binding on projects to fetch the project model right away and pass
     * it as a parameter.
     *
     * @param Request $request
     * @return array
     */
    public function startProjectTimer(Request $request)
    {
        $project = Project::find($request->get('project_id'));
        $timer = Timer::create([
            'project_id' => $project->id,
            'start' => Carbon::now()->toDateTimeString()
        ]);

//        Currently:
//        {
//            'project_id': 1,
//            ...
//        }
//
//        Ideal:
//        {
//            'project' => {
//                'id' => 1,
//                'path'
//            }
//        }

        //Flash
        flash('some flash message');

        //Pusher
        $pusher = new Pusher(env('PUSHER_PUBLIC_KEY'), env('PUSHER_SECRET_KEY'), env('PUSHER_APP_ID'));

        $channel = 'testChannel';
        $event = 'testEvent';
        $data = [
            'payer_id' => $project->payer_id,
            'message' => Auth::user()->name . ' has started a new timer on the project ' . $project->description
        ];

        $pusher->trigger($channel, $event, $data);

        //return response($timer->toArray(), Response::HTTP_CREATED); // = 201 HTTP Created code
        return $this->responseCreated($timer);
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

        //Calculate price
        //Note for developing-price will be zero if time is less than 30 seconds
        // $time = $this->calculateTimerTime($timer->start, $timer->finish);
        //$timer->calculateTotalTime();

        $timer->calculatePrice();

//        $timer->save();

        return $this->responseOk($timer);
//        return [
//            'projects' => $this->projectsRepository->getProjectsResponseForCurrentUser(),
//            'project' => $this->projectsRepository->getProject($project->id)
//        ];
    }

//    /**
//     *
//     * @param $start
//     * @param $finish
//     * @return bool|\DateInterval
//     */
//    private function calculateTimerTime($start, $finish)
//    {
//        $carbon_start = Carbon::createFromFormat('Y-m-d H:i:s', $start);
//        $carbon_finish = Carbon::createFromFormat('Y-m-d H:i:s', $finish);
//        $time = $carbon_finish->diff($carbon_start);
//
//        return $time;
//    }

//    /**
//     *
//     * @param $time
//     * @param $rate
//     * @return float|int
//     */
//    private function getTimerPrice($time, $rate)
//    {
//        $price = 0;
//
//        if ($time->s > 30) {
//            $time->i = $time->i + 1;
//        }
//        $price += $rate * $time->h;
//        $price += $rate / 60 * $time->i;
//
//        return $price;
//    }

    /**
     * Delete a timer
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request, $id)
    {
        $timer = Timer::findOrFail($id);

        $timer->delete();

        return $this->responseNoContent();
    }
}