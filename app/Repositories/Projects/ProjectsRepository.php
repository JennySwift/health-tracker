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

    public function getProjectsArrayForCurrentUser()
    {
        $user = Auth::user();
        $payer = $user;
        $payee = $user;

        return [
            'payee' => $user->projectsAsPayee, // This is a Illuminate\Database\Eloquent\Object
            'payer' => $user->projectsAsPayer
        ];
    }

    public function getProjectsResponseForCurrentUser()
    {
        return response()->json($this->getProjectsArrayForCurrentUser());
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