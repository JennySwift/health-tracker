<?php namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;
use Auth;

/**
 * Class RecipeMethod
 * @package App\Models\Menu
 */
class RecipeMethod extends Model {

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipe()
	{
		return $this->belongsTo('App\Models\Foods\Recipe');
	}

}
