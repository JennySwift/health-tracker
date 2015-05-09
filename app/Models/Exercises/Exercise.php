<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Tags\Tag;

class Exercise extends Model {

	protected $fillable = ['name', 'default_exercise_unit_id', 'description', 'default_quantity', 'step_number', 'series_id'];

	/**
	 * Define relationships
	 */

	public function user () {
	    return $this->belongsTo('App\User');
	}

	public function unit () {
		//the second argument is the name of the field, because if I don't specify it, it will look for unit_id.
	    return $this->belongsTo('App\Models\Units\Unit', 'default_exercise_unit_id');
	}

	public function series () {
	    return $this->belongsTo('App\Models\Exercises\Series');
	}

	public function entries () {
	    return $this->hasMany('App\Models\Exercises\Entry');
	}

	public function tags () {
		return $this->belongsToMany('App\Models\Tags\Tag', 'taggables', 'tag_id', 'taggable_id');
	}

	/**
	 * select
	 */
	
	/**
	 * Get all exercises for the user along with their tags
	 * @return [type] [description]
	 */
	public static function getExercises () {
	    $exercises = static
	    	::where('exercises.user_id', Auth::user()->id)
	    	->leftJoin('units', 'default_exercise_unit_id', '=', 'units.id')
	    	->leftJoin('exercise_series', 'exercises.series_id', '=', 'exercise_series.id')
	    	->select('exercises.id', 'exercises.name', 'exercises.description', 'exercises.step_number', 'exercise_series.name as series_name', 'default_exercise_unit_id', 'default_quantity', 'units.name AS default_exercise_unit_name')
	    	->orderBy('series_name', 'asc')
	    	->orderBy('step_number', 'asc')
	    	->get();

	    //Add the tags to each exercise
	    foreach ($exercises as $exercise) {
	    	$exercise = Exercise::find($exercise->id);
	    	$tags = $exercise->tags;
	    	$exercise->tags = $tags;
	    }

	    return $exercises;
	}

	public static function getExerciseSeriesHistory ($series_id) {
		//I still need functions.php for convertDate
		include(app_path() . '/inc/functions.php');

		//get all exercises in the series
		$exercise_ids = $series->exercises->lists('id');

		//get all entries in the series
		//the function doesn't work when I use the following line:	
		$entries = $series->entries()
			
			->select('exercise_entries.id',
				'date',
				'exercises.id as exercise_id',
				'exercises.name as exercise_name',
				'exercises.description',
				'exercises.step_number',
				'quantity',
				'exercise_unit_id')
			->with(['unit' => function($query) {
				$query->select('name', 'id');
			}])
			// ->with('unit')
			->orderBy('date', 'desc')->get();
		// $entries = ExerciseEntry::getSeriesEntries($exercise_ids);
		
		//create an array to return
		$array = [];

		//populate the array
		foreach ($entries as $entry) {
			$sql_date = $entry->date;
			$date = convertDate($sql_date, 'user');
			$days_ago = getHowManyDaysAgo($sql_date);
			$exercise_id = $entry->exercise_id;
			$exercise_unit_id = $entry->exercise_unit_id;
			$counter = 0;

			$total = ExerciseEntry::getTotalExerciseReps($sql_date, $exercise_id, $exercise_unit_id);

			$sets = ExerciseEntry::getExerciseSets($sql_date, $exercise_id, $exercise_unit_id);

			//check to see if the array already has the exercise entry so it doesn't appear as a new entry for each set of exercises
			foreach ($array as $item) {
				if ($item['date'] === $date && $item['exercise_name'] === $entry->exercise_name && $item['unit_name'] === $entry->unit_name) {
					//the exercise with unit already exists in the array so we don't want to add it again
					$counter++;
				}
			}
			if ($counter === 0) {
				$array[] = array(
					'date' => $date,
					'days_ago' => $days_ago,
					'exercise_id' => $entry->exercise_id,
					'exercise_name' => $entry->exercise_name,
					'description' => $entry->description,
					'step_number' => $entry->step_number,
					'unit_name' => $entry->unit->name,
					'sets' => $sets,
					'total' => $total,
				);
			}	
		}
		
		return $array;
	}

	public static function getDefaultExerciseQuantity ($exercise_id) {
		$quantity = static
			::where('id', $exercise_id)
			->pluck('default_quantity');

		return $quantity;
	}

	public static function getDefaultExerciseUnitId ($exercise_id) {
		$default = static
			::where('id', $exercise_id)
			->pluck('default_exercise_unit_id');

		return $default;
	}
	
	/**
	 * insert
	 */
	
	/**
	 * update
	 */
	
	/**
	 * delete
	 */

}
