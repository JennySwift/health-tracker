<?php namespace App\Repositories\Projects;

use Auth;
use Carbon\Carbon;
use Gravatar;

use App\User;
use App\Models\Projects\Project;
use Illuminate\Support\Facades\DB;

/**
 * Class ProjectsRepository
 * @package App\Repositories\Projects
 */
class ProjectsRepository
{

    /**
     * select
     */

    public function getPayers()
    {
        $user = User::find(Auth::user()->id);

        $payers = $user->payers;

        //Figure out how much the payer owes the payee
        //Add this owed value to the $payer
        //$timer->price has not been coded yet so this won't yet work
        foreach ($payers as $payer) {
            $payer->projects = $payer->projectsAsPayer;

            $owed = 0;
            foreach($payer->projects as $project) {

                foreach($project->timers as $timer) {
                    if (!$timer->paid) {
                        $owed+= $timer->price;
                    }
                }

            }
            $payer->owed = $owed;
        }

        return $payers;
    }

    public function getPayees()
    {
        $user = User::find(Auth::user()->id);
        return $user->payees;
    }

    public function getProjects()
    {
        $user = User::find(Auth::user()->id);

        return [
            'payee' => $user->getProjectsAsPayee(), // This is a Illuminate\Database\Eloquent\Object
            'payer' => $this->getProjectsAsPayer()
        ];
    }

    /**
     * For updating the timers in the project popup,
     * when the user starts and stops a timer.
     * Get the project that the user has selected.
     * @return mixed
     */
    public function getProject($project_id)
    {
        $project = Project::find($project_id)
            ->with('payee')
            ->with('payer')
            ->with('timers')
            ->first();
        $project->total_time_user_formatted = $this->formatTimeForUser($project->total_time);

        return $project;
    }

    public function getProjectsAsPayer()
    {
        $projects = Project::where('payer_id', Auth::user()->id)
            ->with('payee')
            ->with('payer')
            ->with('timers')
            ->get();

        foreach ($projects as $project) {
            $project->total_time_user_formatted = $this->formatTimeForUser($project->total_time);
        }

        return $projects;
    }

    public function formatTimeForUser($time)
    {
        $formatted = [
            'hours' => sprintf("%02d", $time['hours']),
            'minutes' => sprintf("%02d", $time['minutes']),
            'seconds' => sprintf("%02d", $time['seconds'])
        ];

        return $formatted;
    }

    /**
     * insert
     */

    /**
     * Add a payer for the user
     * Return the user's payers
     * @param $payer_email
     * @return mixed
     */
    public function addPayer($payer_email)
    {
        $user = User::find(Auth::user()->id);
        $payer = User::whereEmail($payer_email)->firstOrFail();
        $user->payers()->attach($payer->id);
        $user->save();
        return $user->payers;
    }
    
    public function createProject($payer_email, $description, $rate)
    {
        $project = new Project([
            'description' => $description,
            'rate_per_hour' => $rate
        ]);

        $payer = User::whereEmail($payer_email)->firstOrFail();
        $payee = Auth::user();

        $project->payer()->associate($payer);
        $project->payee()->associate($payee);
        $project->save();
    }

    /**
     * update
     */
    
    /**
     * delete
     */

}