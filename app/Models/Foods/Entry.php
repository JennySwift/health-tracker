<?php namespace App\Models\Foods;

use App\Traits\Models\Relationships\OwnedByUser;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Debugbar;
use App\Models\Foods\Calories;

/**
 * Class Entry
 * @package App\Models\Menu
 */
class Entry extends Model {

    use OwnedByUser;

    /**
     * @var string
     */
    protected $table = 'food_entries';

    /**
     * @var array
     */
    protected $fillable = ['date', 'quantity'];

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
    public function food()
	{
		return $this->belongsTo('App\Models\Foods\Food');
	}
	
	/**
	 * Get a user's food entries for one day
	 * @param  [type] $date [description]
	 * @return [type]       [description]
	 */
	public static function getFoodEntries($date)
	{
		$rows = static
			::join('foods', 'food_entries.food_id', '=', 'foods.id')
			->join('units', 'food_entries.unit_id', '=', 'units.id')
			->leftJoin('recipes', 'food_entries.recipe_id', '=', 'recipes.id')
			->where('date', $date)
			->where('food_entries.user_id', Auth::user()->id)
			->select('food_id', 'foods.name AS food_name', 'food_entries.id AS entry_id', 'units.id AS unit_id', 'units.name AS unit_name', 'quantity', 'recipes.name AS recipe_name', 'recipes.id AS recipe_id')
			->get();
	    

		$food_entries = array();

	    foreach ($rows as $row) {
	    	$food_id = $row->food_id;
	    	$food_name = $row->food_name;
	    	$quantity = $row->quantity;
	    	$entry_id = $row->entry_id;
	    	$unit_id = $row->unit_id;
	    	$unit_name = $row->unit_name;
	    	$recipe_name = $row->recipe_name;
	    	$recipe_id = $row->recipe_id;

	    	$calories_for_item = Food::getCalories($food_id, $unit_id);
	    	$calories_for_quantity = Food::getCaloriesForQuantity($calories_for_item, $quantity);
	    	$calories_for_quantity = number_format($calories_for_quantity, 2);

	    	$food_entries[] = array(
	    		"food_id" => $food_id,
	    		"food_name" => $food_name,
	    		"quantity" => $quantity,
	    		"entry_id" => $entry_id,
	    		"unit_id" => $unit_id,
	    		"unit_name" => $unit_name,
	    		"calories" => $calories_for_quantity,
	    		"recipe_name" => $recipe_name,
	    		"recipe_id" => $recipe_id
	    	);
	    }

	    return $food_entries;
	}

    /**
     *
     * @param $date
     * @param $recipe_id
     * @param $recipe_contents
     */
    public static function insertRecipeEntry($date, $recipe_id, $recipe_contents)
	{
		foreach ($recipe_contents as $item) {
			$food_id = $item['food_id'];
			$quantity = $item['quantity'];
			$unit_id = $item['unit_id'];

			static::insert([
				'date' => $date,
				'food_id' => $food_id,
				'quantity' => $quantity,
				'unit_id' => $unit_id,
				'recipe_id' => $recipe_id,
				'user_id' => Auth::user()->id
			]);
		}
	}

}
