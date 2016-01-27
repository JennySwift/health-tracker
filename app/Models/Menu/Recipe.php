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
		return $this->hasMany('App\Models\Menu\RecipeMethod')->select('id', 'step', 'text');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function foods()
	{
		return $this->belongsToMany('App\Models\Menu\Food', 'food_recipe', 'recipe_id', 'food_id');
	}

    /**
     * Not sure how to how to do the unit relationship because there
     * are three foreign keys in the food_recipe pivot table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
//    public function units()
//    {
//        return $this->belongsToMany('App\Models\Units\Unit', 'food_recipe', 'recipe_id', 'unit_id');
//    }


    /**
     * For the recipe popup.
     * Each ingredient should consist of food, unit, quantity and description.
     * And each food should have its units attached to it.
     *
     * @VP:
     * Is there a better way of doing this?
     * My food_recipe table actually has three foreign keys-
     * recipe_id, food_id, and unit_id.
     * So I'm not sure how to get both the food and the unit for each ingredient,
     * i.e., each row in the food_recipe table,
     * by using relationships instead of DB::table('food_recipe').
     * @return mixed
     */
    public function getIngredients()
    {
        $ingredients = DB::table('food_recipe')
            ->where('recipe_id', $this->id)
            ->join('foods', 'food_id', '=', 'foods.id')
            ->join('units', 'unit_id', '=', 'units.id')
            ->select('foods.id as food_id', 'foods.name', 'units.name as unit_name', 'units.id as unit_id', 'quantity', 'description')
            ->get();

        //Add the units to all the foods in $ingredients
        foreach ($ingredients as $ingredient) {
            $ingredient->units = Food::find($ingredient->food_id)->units;
        }

        return $ingredients;
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this
            ->belongsToMany('App\Models\Tags\Tag', 'taggables', 'taggable_id', 'tag_id')
            ->where('taggable_type', 'recipe');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
//    public function tags()
//    {
//        return $this->morphMany('App\Models\Tags\Tag', 'taggable');
//    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entries()
	{
		return $this->hasMany('App\Models\Menu\Entry');
	}

}