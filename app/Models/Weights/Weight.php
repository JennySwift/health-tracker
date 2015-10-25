<?php namespace App\Models\Weights;

use App\Traits\Models\Relationships\OwnedByUser;
use Illuminate\Database\Eloquent\Model;
use Auth;

/**
 * Class Weight
 * @package App\Models\Weights
 */
class Weight extends Model {

    use OwnedByUser;

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
	{
		return $this->belongsTo('App\User');
	}

}
