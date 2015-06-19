<?php namespace App\Models\Weights;

use Illuminate\Database\Eloquent\Model;
use Auth;

/**
 * Class Weight
 * @package App\Models\Weights
 */
class Weight extends Model {

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
	{
		return $this->belongsTo('App\User');
	}

}
