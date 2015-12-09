<?php

namespace App\Models\Timers;

use App\Traits\Models\Relationships\OwnedByUser;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Activity
 * @package App\Models\Timers
 */
class Activity extends Model
{
    use OwnedByUser;
    /**
     * @var string
     */
    protected $table = 'activities';

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
    public function timers()
    {
        return $this->hasMany('App\Models\Timers\Timer');
    }
}
