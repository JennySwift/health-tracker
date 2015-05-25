<?php  namespace App\Services\Autocomplete;

/**
 * An interface is like a "contract" for classes. All classes that implements this interface must have
 * the same method defined.
 */
interface Autocomplete {

    /**
     * Perform a search given a specific query
     * @param $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search($query);
    
}