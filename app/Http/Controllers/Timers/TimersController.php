<?php namespace App\Http\Controllers\Timers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use JavaScript;
use Auth;
use Carbon\Carbon;

use App\Models\Timers\Timer;

class TimersController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		JavaScript::put([
			'timers' => [
				'payee' => $this->getPayeeTimers(),
				'payer' => $this->getPayerTimers()
			]
		]);

		// return $this->getPayeeTimers();
		return view('timers');
	}

	/**
	 * select
	 */
	
	public function getPayeeTimers()
	{
		$timers = Timer::where('payee_id', Auth::user()->id)
			->with('payee')
			->with('payer')
			->get();

		foreach ($timers as $timer) {
			$timer->times = $this->getTimerTimes($timer);
			$timer->total_time = $this->getTotalTimerTime($timer);
			$timer->total_time_user_formatted = $this->formatTimeForUser($timer->total_time);
			$timer->price = $this->getTotalTimerPrice($timer);
		}

		return $timers;
	}

	public function getPayerTimers()
	{
		$timers = Timer::where('payer_id', Auth::user()->id)
			->with('payee')
			->with('payer')
			->get();

		foreach ($timers as $timer) {
			$timer->times = $this->getTimerTimes($timer);
			$timer->total_time = $this->getTotalTimerTime($timer);
			$timer->total_time_user_formatted = $this->formatTimeForUser($timer->total_time);
			$timer->price = $this->getTotalTimerPrice($timer);
		}

		return $timers;
	}

	/**
	 * Get all times that belong to the $timer,
	 * in other words, each row in the times table that belongs to the timer.
	 * @param  [type] $timer [description]
	 * @return [type]        [description]
	 */
	private function getTimerTimes($timer)
	{
		$times = $timer->times;

		foreach ($times as $time) {
			$start = Carbon::createFromFormat('Y-m-d H:i:s', $time->start);
			$finish = Carbon::createFromFormat('Y-m-d H:i:s', $time->finish);
			//This is the time spent for one time (one row in times table) that belongs to the timer	
			$diff = $finish->diff($start);
			$time->time = $diff;
		}

		return $times;
	}

	/**
	 * Get the total time spent on one timer by adding up the time spent for each time that belongs to the timer.
	 * @param  [type] $timer [description]
	 * @return [type]        [description]
	 */
	private function getTotalTimerTime($timer)
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

	private function formatTimeForUser($time)
	{
		$time = $time['hours'] . ':' . $time['minutes'] . ':' . $time['seconds'];
		return $time;
	}

	private function getTotalTimerPrice($timer)
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
	
	/**
	 * update
	 */
	
	/**
	 * delete
	 */
}
