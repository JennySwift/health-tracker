<?php namespace App\Http\Controllers\Projects;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Projects\Payer;
use JavaScript;
use Auth;

use App\Models\Projects\Payee;
use Illuminate\Http\Request;

class PayeeController extends Controller {

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
     * select
     */

    /**
     * insert
     */

    /**
     * Add a new payer for the user (payee)
     * so that the user can create a project with that person as payer.
     * Return the user's payers
     * @param Request $request
     * @return mixed
     */
    public function addPayer(Request $request)
    {
        $payer_email = $request->get('payer_email');
        $user = User::find(Auth::user()->id);
        $payer = User::whereEmail($payer_email)->firstOrFail();
        $user->payers()->attach($payer->id);
        $user->save();
        return $user->payers;
    }

    /**
     * update
     */

    /**
     * delete
     */

}
