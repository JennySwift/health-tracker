<?php namespace App\Http\Controllers\API\Journal;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\JournalTransformer;
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

    /**
     *
     * @param $typing
     * @return mixed
     */
    public function index($typing)
    {
        $typing = '%' . $typing . '%';
        $matches = Journal::where('user_id', Auth::user()->id)
            ->where('text', 'LIKE', $typing)
            ->orderBy('date', 'desc')
            ->get();

        return transform(createCollection($matches, new JournalTransformer));
    }

    /**
     * @VP:
     * You said I should find the entry by its id here, not its date.
     * But I'm not sure how this can be done since the method is called when the
     * user uses the date navigation, so the id is not known.
     * @param Journal $journal
     * @return mixed
     */
    public function show(Journal $journal)
    {
        return $this->responseOkWithTransformer($journal, new JournalTransformer);
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $journal = new Journal([
            'date' => $request->get('date'),
            'text' => $request->get('text'),
        ]);

        $journal->user()->associate(Auth::user());
        $journal->save();

        return $this->responseCreatedWithTransformer($journal, new JournalTransformer);
    }

    /**
     *
     * @param Request $request
     * @param Journal $journal
     * @return mixed
     */
    public function update(Request $request, Journal $journal)
    {
        $journal->text = $request->get('text');
        $journal->save();

        return $this->responseOkWithTransformer($journal, new JournalTransformer);
    }
}