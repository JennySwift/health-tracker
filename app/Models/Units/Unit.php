<?php namespace App\Models\Units;

use App\Traits\Models\Relationships\OwnedByUser;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

/**
 * Class Unit
 * @package App\Models\Units
 */
class Unit extends Model {

    use OwnedByUser;

    /**
     * @var array
     */
    protected $fillable = ['name', 'for'];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function foods()
	{
		return $this->belongsToMany('App\Models\Foods\Food');
	}
}
