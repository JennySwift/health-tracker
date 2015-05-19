<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;
use Auth;
use App\Models\Tags\Tag;
use App\Models\Exercises\Series;
use App\Models\Exercises\Entry as ExerciseEntry;

class Exercise extends Model {

	use OwnedByUser;

	protected $fillable = ['name', 'default_exercise_unit_id', 'description', 'default_quantity', 'step_number', 'series_id'];

	/**
	 * Define relationships
	 */

	public function user()
	{
	    return $this->belongsTo('App\User');
	}

	public function unit()
	{
		//the second argument is the name of the field, because if I don't specify it, it will look for unit_id.
	    return $this->belongsTo('App\Models\Units\Unit', 'default_exercise_unit_id');
	}

	public function series()
	{
	    return $this->belongsTo('App\Models\Exercises\Series');
	}

	public function entries()
	{
	    return $this->hasMany('App\Models\Exercises\Entry');
	}

	public function tags()
	{
		return $this->belongsToMany('App\Models\Tags\Tag', 'taggables', 'taggable_id', 'tag_id')->where('taggable_type', 'exercise');
	}

	/**
	 * select
	 */
	
	/**
	 * Get all exercises for the current user,
	 * along with their tags, default unit name and the name of the series each exercise belongs to.
	 * Order first by series name, then by step number.
	 * I haven't yet ordered by series name.
	 * @return [type] [description]
	 */
	public static function getExercises () {
		$exercises = static::forCurrentUser('exercises')
			->with('unit')
			->with('series')
			->with('tags')
			->orderBy('step_number')
			->get();

	    return $exercises;
	}

	public static function getExerciseSeriesHistory($series)
	{
		//get all exercises in the series
		$exercise_ids = $series->exercises->lists('id');

		//get all entries in the series
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
		
		//create an array to return
		$array = [];

		//populate the array
		foreach ($entries as $entry) {
			$sql_date = $entry->date;
			$date = static::convertDate($sql_date, 'user');
			$days_ago = static::getHowManyDaysAgo($sql_date);
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

	public static function getDefaultExerciseQuantity($exercise_id)
	{
		$quantity = static
			::where('id', $exercise_id)
			->pluck('default_quantity');

		return $quantity;
	}

	public static function getDefaultExerciseUnitId($exercise_id)
	{
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
	
	/**
	 * other
	 */
	
	public static function convertDate($date, $for)
	{
		//Use Carbon here
		$date = new DateTime($date);

		if ($for === 'user') {
			$date = $date->format('d/m/y');
		}
		elseif ($for === 'sql') {
			$date = $date->format('Y-m-d');
		}
		return $date;
	}

	/**
	 * Find out how many days ago a date was
	 * Use Carbon
	 * @param  [type] $date [description]
	 * @return [type]       [description]
	 */
	public static function getHowManyDaysAgo($date)
	{
		$now = new DateTime('now');
		$date = new DateTime($date);
		$diff = $now->diff($date);
		$days_ago = $diff->days;
		return $days_ago;
	}

}
