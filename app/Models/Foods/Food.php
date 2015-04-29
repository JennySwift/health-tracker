<?php namespace App\Models\Foods;

use Illuminate\Database\Eloquent\Model;
use App\Models\Foods\Calories;
use Auth;

class Food extends Model {

	protected $fillable = ['name'];

	public function user () {
		return $this->belongsTo('App\User');
	}

	public static function autocompleteMenu ($typing) {
		$typing = '%' . $typing . '%';
		
		$menu = DB::select("select * from (select id, name, 'food' as type from foods where name LIKE '$typing' and user_id = " . Auth::user()->id . " UNION select id, name, 'recipe' as type from recipes where name LIKE '$typing' and user_id = " . Auth::user()->id . ") as table1 order by table1.name asc");

		return $menu;
	}

	public static function insertFood ($name) {
		// static::insert([
		// 	'name' => $name,
		// 	'user_id' => Auth::user()->id
		// ]);
		// 
		$food = new static(['name' => $name]);
		$food->user()->associate(Auth::user());
		$food->save();

		return $food;
	}

	public static function getFoods () {
		$query = static
			::where('user_id', Auth::user()->id)
			->orderBy('name', 'asc')->get();

		$foods = array();
		foreach ($query as $food) {
			$food_id = $food->id;
			$food_name = $food->name;
			
			$foods[] = array(
				"id" => $food_id,
				"name" => $food_name
			);
		}

		return $foods;
	}

	public static function getAllFoodsWithUnits () {
		$foods = static::getFoods();
		$all_foods_with_units = array();

		foreach ($foods as $food) {
			$food_id = $food['id'];
			$food_name = $food['name'];

		    $food = array(
				"id" => $food_id,
				"name" => $food_name
		    );

			$rows = Calories
				::join('foods', 'food_id', '=', 'foods.id')
				->join('food_units', 'calories.unit_id', '=', 'food_units.id')
				->where('food_id', $food_id)
				->select('food_units.name', 'food_units.id', 'calories', 'default_unit')
				->get();

			$units = array();
			foreach ($rows as $row) {
				$unit_name = $row->name;
				$unit_id = $row->id;
				$calories = $row->calories;
				$default_unit = $row->default_unit;

				if ($default_unit === 1) {
					$default_unit = true;
					$default_unit_id = $unit_id;
					$default_unit_name = $unit_name;
					$default_unit_calories = $calories;

					$food['default_unit_id'] = $default_unit_id;
					$food['default_unit_name'] = $default_unit_name;
					$food['default_unit_calories'] = $default_unit_calories;
				}
				else {
					$default_unit = false;
				}

				$units[] = array(
					"id" => $unit_id,
					"name" => $unit_name,
					"calories" => $calories,
					"default_unit" => $default_unit
				);
			}

		    $all_foods_with_units[] = array(
		    	"food" => $food,
		    	"units" => $units
		    );
		}
	    
		return $all_foods_with_units;
	}

	public static function insertFoodIfNotExists ($food_name) {
		//for quick recipe
		include(app_path() . '/inc/functions.php');
		$count = countItem('foods', $food_name);

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
			$food_id = getId('foods', $food_name);
		}

		return $food_id;
	}

}
