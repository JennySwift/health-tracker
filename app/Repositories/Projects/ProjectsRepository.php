<?php namespace App\Repositories\Projects;

use Auth;
use Carbon\Carbon;
use Gravatar;

use App\User;
use App\Models\Projects\Project;

/**
 * Class ProjectsRepository
 * @package App\Repositories\Projects
 */
class ProjectsRepository
{

    /**
     * select
     */
    
    public function getGravatar($email)
    {
        return Gravatar::src($email);
        // return Gravatar::exists('cheezyspaghetti@gmail.com');
    }
    
    public function getProjectsAsPayee()
    {
        $projects = Project::where('payee_id', Auth::user()->id)
            ->with('payee')
            ->with('payer')
            ->get();

        foreach ($projects as $project) {
            $project->timers = $this->getProjectTimers($project);
            $project->total_time = $this->getProjectTotalTime($project);
            $project->total_time_user_formatted = $this->formatTimeForUser($project->total_time);
            $project->price = $this->getProjectPrice($project);

            //Add gravatars
            $project->payee->gravatar = $this->getGravatar($project->payee->email);
            $project->payer->gravatar = $this->getGravatar($project->payer->email);
        }

        return $projects;
    }

    public function getProjectsAsPayer()
    {
        $projects = Project::where('payer_id', Auth::user()->id)
            ->with('payee')
            ->with('payer')
            ->get();

        foreach ($projects as $project) {
            $project->timers = $this->getProjectTimers($project);
            $project->total_time = $this->getProjectTotalTime($project);
            $project->total_time_user_formatted = $this->formatTimeForUser($project->total_time);
            $project->price = $this->getProjectPrice($project);

            //Add gravatars
            $project->payee->gravatar = $this->getGravatar($project->payee->email);
            $project->payer->gravatar = $this->getGravatar($project->payer->email);
        }

        return $projects;
    }

    /**
     * Get all times that belong to the $timer,
     * in other words, each row in the times table that belongs to the timer.
     * @param  [type] $timer [description]
     * @return [type]        [description]
     */
    public function getProjectTimers($project)
    {
        $timers = $project->timers;

        foreach ($timers as $timer) {
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $timer->start);
            $finish = Carbon::createFromFormat('Y-m-d H:i:s', $timer->finish);
            //This is the time spent for one time (one row in times table) that belongs to the timer    
            $diff = $finish->diff($start);
            $timer->time = $diff;
        }

        return $timers;
    }

    /**
     * Get the total time spent on one timer by adding up the time spent for each time that belongs to the timer.
     * @param  [type] $timer [description]
     * @return [type]        [description]
     */
    public function getProjectTotalTime($project)
    {
        $hours = 0;
        $minutes = 0;
        $seconds = 0;

        foreach ($project->timers as $timer) {
            //Calculate hours, minutes and seconds
            $hours+= $timer->time->h;
            $minutes+= $timer->time->i;
            $seconds+= $timer->time->s;
        }

        $total_time = [
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds
        ];

        return $total_time;
    }

    public function formatTimeForUser($time)
    {
        $time = $time['hours'] . ':' . $time['minutes'] . ':' . $time['seconds'];
        return $time;
    }

    public function getProjectPrice($project)
    {
        $rate = $project->rate_per_hour;
        $time = $project->total_time;
        $price = 0;

        $price+= $rate * $time['hours'];
        $price+= $rate / 60 * $time['minutes'];
        $price+= $rate / 3600 * $time['seconds'];

        return $price;
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
    
    public function startTimer()
    {

    }

    /**
     * update
     */
    
    /**
     * delete
     */

}