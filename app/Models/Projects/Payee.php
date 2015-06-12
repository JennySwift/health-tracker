<?php namespace App\Models\Projects;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Payee extends User {

	/**
     * Define relationships
     */

    public function payers()
    {
        return $this->belongsToMany('App\User', 'payee_payer', 'payee_id', 'payer_id');
    }

    /**
     * Get all the projects where the user is the payee
     */
    public function projectsAsPayee()
    {
        return $this->hasMany('App\Models\Projects\Project', 'payee_id');
    }

//    public function getOwed()
//    {
//        $payee = Payee::find(Auth::user()->id);
////        dd($payee);
//        $payers = $payee->payers;
////        dd($payers);
//
//        foreach ($payers as $payer) {
//            $payer->owed = $this->getAmountOwed();
//        }
//
////        Figure out how much the payer owes the payee
////        Add this owed value to the $payer
////        $timer->price has not been coded yet so this won't yet work
////        foreach ($payers as $payer) {
////            $payer->projectsAsPayer = $payer->projectsAsPayer;
//////            dd($payer->projectsAsPayer);
////
////            $owed = 0;
////            foreach($payer->projectsAsPayer as $project) {
////
////                foreach($project->timers as $timer) {
////                    if (!$timer->paid) {
////                        $owed+= $timer->price;
////                    }
////                }
////
////            }
////            $payer->owed = $owed;
////        }
////        dd($payers);
//        return $payers;
//    }

}
