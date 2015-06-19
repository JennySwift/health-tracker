<?php namespace App\Models\Projects;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Projects\Timer;

class Payer extends User {

	/**
     * Define relationships
     */

    /**
     * Get all the projects where the user is the payer
     */
    public function projects()
    {
        return $this->hasMany('App\Models\Projects\Project', 'payer_id');
    }

    public function payees()
    {
        return $this->belongsToMany('App\User', 'payee_payer', 'payer_id', 'payee_id');
    }
}
