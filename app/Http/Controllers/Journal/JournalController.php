<?php namespace App\Http\Controllers\Journal;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Weights\WeightsRepository;
use Illuminate\Http\Request;
use Auth;
use Debugbar;
use Carbon\Carbon;
use JavaScript;

/**
 * Models
 */
use App\Models\Journal\Journal;

class JournalController extends Controller {
	
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
	 * Index
	 */

	public function index()
	{
		$date = Carbon::today()->format('Y-m-d');

		JavaScript::put([
			'entry' => Journal::getJournalEntry($date)
		]);

		return view('journal');
	}

	/**
	 * select
	 */
	
	public function getJournalEntry(Request $request)
	{
		$date = $request->get('date');
		return Journal::getJournalEntry($date);
	}

	/**
	 * insert
	 */
	public function insertOrUpdateJournalEntry(Request $request)
	{
		//inserts or updates journal entry
		$date = $request->get('date');
		$text = $request->get('text');
		Journal::insertOrUpdateJournalEntry($date, $text);

		return Journal::getJournalEntry($date);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	// public function index()
	// {
	// 	//
	// }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
