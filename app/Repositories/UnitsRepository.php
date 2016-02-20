<?php

namespace App\Repositories;


use App\Models\Units\Unit;
use DB;
use Auth;

/**
 * Class UnitsRepository
 * @package App\Repositories
 */
class UnitsRepository {

    /**
     *
     * @return mixed
     */
    public function getExerciseUnits()
    {
        return Unit::forCurrentUser()
            ->where('for', 'exercise')
            ->orderBy('name', 'asc')
            ->get();

    }

}