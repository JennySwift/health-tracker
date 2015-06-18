<?php namespace App\Http\Controllers\Projects;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Projects\Payer;
use App\User;
use JavaScript;
use Auth;

use App\Models\Projects\Payee;
use Illuminate\Http\Request;

/**
 * Class PayeeController
 * @package App\Http\Controllers\Projects
 */
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
        $payee = Payee::find($user->id);
        $payer = User::whereEmail($payer_email)->firstOrFail();
        $payee->payers()->attach($payer->id);
        $user->save();

        return $payee->payers;
    }

    /**
     * Remove a relationship between a payee and a payer,
     * and all associated projects
     * @param Request $request
     * @return mixed
     */
    public function removePayer(Request $request)
    {
        $payer = Payer::find($request->get('payer_id'));
        $payee = Payee::find(Auth::user()->id);
        //Remove the relationship between the payee and the payer
        //from the payee_payer table
        $payee->payers()->detach($payer->id);
        $payee->save();

        //Remove projects the payee had with the payer
        /**
         * @VP:
         * Is there some way I could do this instead in the migrations file,
         * like with cascade on delete?
         */
        $payee->projectsAsPayee()->where('payer_id', $payer->id)->delete();

        //Todo: The page needs refreshing to see that the associated projects
        //Todo: have been deleted, since I am not returning the projects here.
        return $payee->payers;
    }
}