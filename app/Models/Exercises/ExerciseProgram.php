<?php

namespace App\Models\Exercises;

use App\Traits\Models\Relationships\OwnedByUser;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ExerciseProgram
 * @package App
 */
class ExerciseProgram extends Model
{
    use OwnedByUser;
    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exercises()
    {
        return $this->hasMany('App\Models\Exercises\Exercise');
    }
}
