<?php namespace App\Repositories\Projects;

use Auth;
use Carbon\Carbon;

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
    
    public function getProjectsAsPayee()
    {
        $timers = Project::where('payee_id', Auth::user()->id)
            ->with('payee')
            ->with('payer')
            ->get();

        foreach ($timers as $timer) {
            $timer->times = $this->getProjectTimers($timer);
            $timer->total_time = $this->getProjectTotalTime($timer);
            $timer->total_time_user_formatted = $this->formatTimeForUser($timer->total_time);
            $timer->price = $this->getProjectPrice($timer);
        }

        return $timers;
    }

    public function getProjectsAsPayer()
    {
        $timers = Project::where('payer_id', Auth::user()->id)
            ->with('payee')
            ->with('payer')
            ->get();

        foreach ($timers as $timer) {
            $timer->times = $this->getProjectTimers($timer);
            $timer->total_time = $this->getProjectTotalTime($timer);
            $timer->total_time_user_formatted = $this->formatTimeForUser($timer->total_time);
            $timer->price = $this->getProjectPrice($timer);
        }

        return $timers;
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
    public function getProjectTotalTime($timer)
    {
        $hours = 0;
        $minutes = 0;
        $seconds = 0;

        foreach ($timer->times as $time) {
            //Calculate hours, minutes and seconds
            $hours+= $time->time->h;
            $minutes+= $time->time->i;
            $seconds+= $time->time->s;
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

    public function getProjectPrice($timer)
    {
        $rate = $timer->rate_per_hour;
        $time = $timer->total_time;
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