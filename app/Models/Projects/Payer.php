<?php namespace App\Models\Projects;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Payer extends User {

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
