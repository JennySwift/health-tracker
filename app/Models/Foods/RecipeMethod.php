<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use Auth;

/**
 * Class RecipeMethod
 * @package App\Models\Menu
 */
class RecipeMethod extends Model {

    /**
     * @JS: The recipe_id field shouldn't have to be in the fillable property, it means that we'll have some work
     * with your insert/update with relationships
     * @var array
     */
    protected $fillable = ['recipe_id'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipe()
	{
		return $this->belongsTo('App\Models\Foods\Recipe');
	}

    /**
     *
     * @param $recipe
     * @return mixed
     */
    public static function getRecipeSteps($recipe)
	{
		$steps = $recipe->steps;
		return $steps;
	}

    /**
     *
     * @param $recipe
     * @param $steps
     */
    public static function insertRecipeMethod($recipe, $steps)
	{
		$step_number = 0;
		foreach ($steps as $step_text) {
			$step_number++;

			static
				::insert([
					'recipe_id' => $recipe->id,
					'step' => $step_number,
					'text' => $step_text,
					'user_id' => Auth::user()->id
				]);
		}
	}

    /**
     *
     * @param $recipe
     */
    public static function deleteRecipeMethod($recipe)
	{
		static
			::where('recipe_id', $recipe->id)
			->delete();
	}

}
