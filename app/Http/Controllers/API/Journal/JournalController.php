<?php namespace App\Http\Controllers\API\Journal;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Journal\Journal;
use Auth;
use Debugbar;
use Illuminate\Http\Request;
use JavaScript;


/**
 * Class JournalController
 * @package App\Http\Controllers\Journal
 */
class JournalController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function filter(Request $request)
    {
        $typing = '%' . $request->get('typing') . '%';
        $matches = Journal::where('user_id', Auth::user()->id)
            ->where('text', 'LIKE', $typing)
            ->orderBy('date', 'desc')
            ->get();

        return $matches;
    }


    /**
     * @VP:
     * You said I should find the entry by its id here, not its date.
     * But I'm not sure how this can be done since the method is called when the
     * user uses the date navigation, so the id is not known.
     * @param $date
     * @return array
     */
    public function show($date)
    {
        return Journal::getJournalEntry($date);
    }

    /**
     *
     * @param Request $request
     * @return static
     */
    public function store(Request $request)
    {
        $journal = new Journal([
            'date' => $request->get('date'),
            'text' => $request->get('text'),
        ]);

        $journal->user()->associate(Auth::user());
        $journal->save();

        return $this->responseCreated($journal);
    }

    /**
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Support\Collection|null|static
     */
    public function update(Request $request, Journal $journal)
    {
        $journal->text = $request->get('text');
        $journal->save();

        return $journal;
    }
}