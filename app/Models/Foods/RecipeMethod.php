<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use Auth;

class RecipeMethod extends Model {

    /**
     * @JS: The recipe_id field shouldn't have to be in the fillable property, it means that we'll have some work
     * with your insert/update with relationships
     * @var array
     */
    protected $fillable = ['recipe_id'];

	/**
	 * Define relationships
	 */

	public function recipe()
	{
		return $this->belongsTo('App\Models\Foods\Recipe');
	}

	/**
	 * select
	 */
	
	public static function getRecipeSteps($recipe_id)
	{
		$recipe = Recipe::find($recipe_id);
		$steps = $recipe->steps;
		return $steps;
	}

	/**
	 * insert
	 */
	
	public static function insertRecipeMethod($recipe_id, $steps)
	{
		$step_number = 0;
		foreach ($steps as $step_text) {
			$step_number++;

			static
				::insert([
					'recipe_id' => $recipe_id,
					'step' => $step_number,
					'text' => $step_text,
					'user_id' => Auth::user()->id
				]);
		}
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */

	public static function deleteRecipeMethod($recipe_id)
	{
		static
			::where('recipe_id', $recipe_id)
			->delete();
	}

}
