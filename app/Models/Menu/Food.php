<?php namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;
use App\Models\Menu\Entry;
use App\Models\Units\Unit;
use Auth;
use App\User;
use DB;
use Debugbar;
use Carbon\Carbon;

/**
 * Class Food
 * @package App\Models\Menu
 */
class Food extends Model {

	use OwnedByUser;

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @var array
     */
    protected $appends = ['path'];

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
    public function entries()
	{
		return $this->hasMany('App\Models\Foods\Entry');
	}

    /**
     * Not sure if the 3rd and 4th parameters should be switched here.
     * I had to switch them in the recipe model.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	public function recipes()
	{
		return $this->belongsToMany('App\Models\Foods\Recipe', 'food_recipe', 'food_id', 'recipe_id');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function units()
	{
		return $this->belongsToMany('App\Models\Units\Unit');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function defaultUnit()
	{
		return $this->belongsTo('App\Models\Units\Unit', 'default_unit_id', 'id');
	}

    /**
     * Return the URL of the project
     * it needs to be called getFieldAttribute
     * @return string
     */
    public function getPathAttribute()
    {
        return route('api.foods.show', $this->id);
    }

    /**
     *
     * @return mixed
     */
    public function getDefaultCalories()
    {
        return DB::table('food_unit')
            ->where('food_id', $this->id)
            ->where('unit_id', $this->default_unit_id)
            ->pluck('calories');
    }

    /**
     *
     * @param $food_name
     * @return mixed
     */
    public function insertFoodIfNotExists($food_name)
    {
        //for quick recipe
        $count = static::countItem('foods', $food_name);

        if ($count < 1) {
            //the food does not exist. create the new food.
            $food_id = static
                ::insertGetId([
                    'name' => $food_name,
                    'user_id' => Auth::user()->id
                ]);
        }
        else {
            //the food exists. retrieve the id of the food
            $food_id = static::getId('foods', $food_name);
        }

        return $food_id;
    }

}
