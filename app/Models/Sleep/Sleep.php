<?php

namespace App\Models\Sleep;

use App\Traits\Models\Relationships\OwnedByUser;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sleep
 * @package App\Models\Sleep
 */
class Sleep extends Model
{
    use OwnedByUser;

    /**
     * @var string
     */
    protected $table = 'sleep';

    /**
     * @var array
     */
    protected $fillable = ['start', 'finish'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
