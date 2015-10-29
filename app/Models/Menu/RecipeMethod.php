<?php namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;
use Auth;

/**
 * Class RecipeMethod
 * @package App\Models\Menu
 */
class RecipeMethod extends Model {

    protected $fillable = ['step', 'text'];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipe()
	{
		return $this->belongsTo('App\Models\Menu\Recipe');
	}

}
