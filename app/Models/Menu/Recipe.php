<?php namespace App\Models\Menu;

use App\Traits\Models\Relationships\OwnedByUser;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Debugbar;

/**
 * Class Recipe
 * @package App\Models\Menu
 */
class Recipe extends Model {

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
    public function steps()
	{
		return $this->hasMany('App\Models\Foods\RecipeMethod');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function foods()
	{
		return $this->belongsToMany('App\Models\Foods\Food', 'food_recipe', 'recipe_id', 'food_id');
	}

    /**
     *
     * @return mixed
     */
    public function tags()
	{
		return $this->belongsToMany('App\Models\Tags\Tag', 'taggables', 'taggable_id', 'tag_id')->where('taggable_type', 'recipe');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entries()
	{
		return $this->hasMany('App\Models\Foods\Entry');
	}

}