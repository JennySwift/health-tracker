<?php  namespace App\Traits\Models\Relationships;

use Auth;

/**
 * Define you user (relationship) related methods here
 * @package App\Traits\Models\Scopes
 */
trait OwnedByUser {

    /**
     * User relationship
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Will give you a shortcut to select food for the current user ;)
     * @param $query
     * @return mixed
     */
    // public function scopeForCurrentUser($query, $table = null)
    // {

    //     // Make sure the user is logged in, we never know. If so, modify the query by adding a whereUserId clause to
    //     // it with the current user id
    //     if (Auth::check()) {
    //         return $query->whereUserId(Auth::user()->id);
    //     }

    //     return $query;
    // }
    public function scopeForCurrentUser($query, $table = null)
    {
        if(is_null($table)) {
          
          // Make sure the user is logged in, we never know. If so, modify the query by adding a whereUserId clause to
          // it with the current user id
          if (Auth::check()) {
              return $query->whereUserId(Auth::user()->id);        
          }
        }  

        return $query->where($table.'.user_id', Auth::user()->id);
        
    }

}