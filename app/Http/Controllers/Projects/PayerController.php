<?php namespace App\Http\Controllers\Projects;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Projects\Payer;
use JavaScript;
use Auth;

use Illuminate\Http\Request;

class PayerController extends Controller {

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
        $payer = Payer::find(Auth::user()->id);

        JavaScript::put([
            'payer_projects' => $payer->projectsAsPayer,
            'payees' => $payer->payees
        ]);

        return view('payer');
    }

}
