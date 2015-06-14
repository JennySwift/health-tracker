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
	 * Select
	 */

	public function index()
	{
		$date = Carbon::today()->format('Y-m-d');

		JavaScript::put([
			'entry' => Journal::getJournalEntry($date)
		]);

		return view('journal');
	}

    //Todo: This should take the id, not the date (see binding thing in routes.php)
    public function show($journal)
    {
        return $journal;
    }
	
//	public function getJournalEntry(Request $request)
//	{
//		$date = $request->get('date');
//		return Journal::getJournalEntry($date);
//	}

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
}