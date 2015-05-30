<?php namespace App\Http\Controllers\Timers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use JavaScript;
use Auth;

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

		// return $this->getPayerTimers();
		return view('timers');
	}

	/**
	 * select
	 */
	
	public function getPayeeTimers()
	{
		return Timer::where('payee_id', Auth::user()->id)
			->with('payee')
			->with('payer')
			->get();
	}

	public function getPayerTimers()
	{
		return Timer::where('payer_id', Auth::user()->id)
			->with('payee')
			->with('payer')
			->get();
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
