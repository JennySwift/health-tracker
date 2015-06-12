<?php namespace App\Models\Projects;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Projects\Timer;

class Payer extends User {

    protected $appends = ['owed'];

    /**
     * Appends
     */

//    public function getOwedAttribute()
//    {
////        DB::table('projects')->where('payee_id', $payee_id)
////            ->where('payer_id', $payer_id)
//
//        $owed = Timer::whereIn('id', [1,2,3])
//            ->sum('price');
//
//        return $owed;
//    }

	/**
     * Define relationships
     */

    /**
     * Get all the projects where the user is the payer
     * and the logged in user is payee
     */
//    public function projectsAsPayer()
//    {
//        return $this->hasMany('App\Models\Projects\Project', 'payer_id')
//            ->with('payee')
//            ->with('payer');
//    }

    /**
     * Get all the projects where the user is the payer
     */
    public function projectsAsPayer()
    {
        return $this->hasMany('App\Models\Projects\Project', 'payer_id');
    }

    public function payees()
    {
        return $this->belongsToMany('App\User', 'payee_payer', 'payer_id', 'payee_id');
    }
}
