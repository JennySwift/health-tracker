<?php namespace App\Models\Projects;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Payee extends User
{

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
    public function projects()
    {
        return $this->hasMany('App\Models\Projects\Project', 'payee_id');
    }

}
