<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;
use Auth;
use App\Models\Exercises\Exercise;
use App\Models\Exercises\Entry as ExerciseEntry;
use App\Models\Units\Unit;

class Entry extends Model {

	use OwnedByUser;

    protected $fillable = ['date', 'quantity'];

	protected $table = 'exercise_entries';

	/**
	 * Define relationships
	 */

	public function user()
	{
	    return $this->belongsTo('App\User');
	}

	public function exercise()
	{
	    return $this->belongsTo('App\Models\Exercises\Exercise');
	}

	public function unit()
	{
	    return $this->belongsTo('App\Models\Units\Unit', 'exercise_unit_id');
	}

	public function entries()
	{
		return $this->hasMany('App\Models\Exercises\Entry');
	}

	/**
	 * select
	 */
	
	/**
	 * Get all exercise entries belonging to the user on a specific date
	 * @param  [type] $date [description]
	 * @return [type]       [description]
	 */
	public static function getExerciseEntries ($date) {
		$entries = static::forCurrentUser('exercise_entries')
			->where('date', $date)
			->with('exercise')
			->with('unit')
			->get();

		$entries = static::compactExerciseEntries($entries, $date);
		
		return $entries;
	}

	/**
	 * For getExerciseEntries.
	 * If entries share the same exercise and unit, compact them into one item.
	 * Include the default unit id so I can show the 'add set' button only if the entry uses the default unit.
	 *
	 * @VP:
	 * This method is much the same as Exercise::compactExerciseEntries, but slightly different.
	 * Should I combine them into one method or keep them separate?
	 * 
	 * @param  [type] $entries [description]
	 * @return [type]          [description]
	 */
	public static function compactExerciseEntries($entries, $date)
	{
		// return $entries;
		//create an array to return
		$array = [];

		//populate the array
		foreach ($entries as $entry) {
			$counter = 0;

			//check to see if the array already has the exercise entry
			//so it doesn't appear as a new entry for each set of exercises
			if (count($array) > 0) {
				foreach ($array as $item) {
					if ($item['name'] === $entry->exercise->name && $item['unit_name'] === $entry->unit->name) {
						//the exercise with unit already exists in the array
						//so we don't want to add it again
						$counter++;
					}
				}
			}
			if ($counter === 0) {
				$array[] = array(
					'exercise_id' => $entry->exercise_id,
					'name' => $entry->exercise->name,
					'description' => $entry->exercise->description,
					'step_number' => $entry->exercise->step_number,
					'unit_id' => $entry->unit->id,
					'unit_name' => $entry->unit->name,
					'default_unit_id' => $entry->exercise->default_unit_id,
					'sets' => ExerciseEntry::getExerciseSets($date, $entry->exercise_id, $entry->exercise_unit_id),
					'total' => ExerciseEntry::getTotalExerciseReps($date, $entry->exercise_id, $entry->exercise_unit_id),
					'quantity' => $entry->quantity,
				);
			}
		}

		return $array;
	}

	/**
	 * Get all entries for one exercise with a particular unit on a particular date.
	 * Get exercise name, quantity, and entry id.
	 * @param  [type] $date             [description]
	 * @param  [type] $exercise_id      [description]
	 * @param  [type] $exercise_unit_id [description]
	 * @return [type]                   [description]
	 */
	public static function getSpecificExerciseEntries($date, $exercise, $exercise_unit_id) {
		$entries = static::where('exercise_id', $exercise->id)
			->where('date', $date)
			->where('exercise_unit_id', $exercise_unit_id)
			->with('exercise')
			->get();

		$unit = Unit::find($exercise_unit_id);

		return [
			'entries' => $entries,
			'exercise' => $exercise,
			'unit' => $unit
		];
	}
	
	// public static function getSpecificExerciseEntries($date, $exercise, $exercise_unit_id) {
	// 	// dd($exercise_unit_id);
	// 	$entries = static::where('date', $date)
	// 		->with([
	// 			'exercise' => function($query) use ($exercise)
	// 			{
	// 				$query->whereId($exercise->id);
	// 			}, 'unit' => function($query) use ($exercise_unit_id)
	// 			{
	// 				$query->whereId($exercise_unit_id);
	// 			}
	// 		])->get();

	// 	return $entries;
	// }

	// public static function getSpecificExerciseEntries($date, $exercise, $exercise_unit_id) {
	// 	$entries = ExerciseEntry
	// 		::where('date', $date)
	// 		->where('exercise_id', $exercise->id)
	// 		->where('exercise_unit_id', $exercise_unit_id)
	// 		->join('exercises', 'exercise_entries.exercise_id', '=', 'exercises.id')
	// 		->join('units', 'exercise_entries.exercise_unit_id', '=', 'units.id')
	// 		->select('exercise_id', 'quantity', 'exercises.name', 'units.name AS unit_name', 'exercise_entries.id AS entry_id')
	// 		->orderBy('exercises.name', 'asc')
	// 		->get();
	// 	return $entries;
	// }
	
	/**
	 * Get all entries for one exercise with a particular unit on a particular date
	 * @param  [type] $date             [description]
	 * @param  [type] $exercise_id      [description]
	 * @param  [type] $exercise_unit_id [description]
	 * @return [type]                   [description]
	 */
	// public static function getSpecificExerciseEntries($date, $exercise, $exercise_unit_id)
	// {
	// 	dd($exercise);
	// 	/**
	// 	 * The following query works how I want it to 
	// 	 */
		
	// 	// $entries = ExerciseEntry
	// 	// 	::where('date', $date)
	// 	// 	->where('exercise_id', $exercise->id)
	// 	// 	->where('exercise_unit_id', $exercise_unit_id)
	// 	// 	->join('exercises', 'exercise_entries.exercise_id', '=', 'exercises.id')
	// 	// 	->join('units', 'exercise_entries.exercise_unit_id', '=', 'units.id')
	// 	// 	->select('exercise_id', 'quantity', 'exercises.name', 'units.name AS unit_name', 'exercise_entries.id AS entry_id')
	// 	// 	->orderBy('exercises.name', 'asc')
	// 	// 	->get();
		
	// 	// $entries = $exercise::with('entries')->get();
		
	// 	/**
	// 	 * @VP:
	// 	 * The following query gets every exercise.
	// 	 * I want it to only get one exercise, i.e., $exercise.
	// 	 * The line above this comment get only the exercise I want,
	// 	 * so why is the following getting every exercise?
	// 	 *
	// 	 * If you want to test the response in Postman,
	// 	 * you can use my seeders and here is the info for Postman:
	// 	 * route: select/specificExerciseEntries
	// 	 * date: the current date
	// 	 * exercise_id: 1
	// 	 * exercise_unit_id: 1
	// 	 */

	// 	// $entries = $exercise::with(['entries' => function($query) use ($date, $exercise_unit_id)
	// 	// {
	// 	// 	$query
	// 	// 		->where('date', $date)
	// 	// 		->where('exercise_unit_id', $exercise_unit_id)
	// 	// 		// ->with('unit')
	// 	// 		*
	// 	// 		 * @VP:
	// 	// 		 * The above line worked but I only need the unit name.
	// 	// 		 * Why does the following not work and how do I get only the unit name?
				 		
	// 	// 		// ->with(['unit' => function ($query) {
	// 	// 		// 	$query->select('name');
	// 	// 		// }]);
	// 	// }])->get();

	// 	return $entries;

	// 	/**
	// 	 * @VP:
	// 	 * Could you also give me an example here please of how you would view the above query (in Postman?)?
	// 	 * I tried replacing ->get() with ->toSql(), and all it said was:
	// 	 * select * from `exercises`
	// 	 * rather than the entire query
	// 	 */
 //        /**
 //         * @JS: And this was correct, that is what you are doing apparently!! lol
 //         */
		
			
	// 	/**
	// 	 * When I try the following I get:
	// 	 * Error: Missing argument 2 for Illuminate\Database\Query\Builder::whereDate()
	// 	 */
 //        /**
 //         * You cannot use the shortcut whereField on a field named "date" simply because the
 //         * Illuminate\Database\Query\Builder already has a whereDate method, which is a little different. Try doing
 //         * it this way:
 //         *
 //         * whereDate('date', $date)
 //         *
 //         * or you can keep the where('date', $date) as well
 //         */
		
	// 	// $entries = ExerciseEntry::whereDate($date)->with(['exercise' => function($query) use ($exercise_id)
	// 	// {
	// 	//     $query->whereId($exercise_id);
	// 	//     $query->orderBy('name');
	// 	// }])->get();
	// }

	public static function getExerciseSets($date, $exercise_id, $exercise_unit_id)
	{
		return static
			::where('date', $date)
			->where('exercise_id', $exercise_id)
			->where('exercise_unit_id', $exercise_unit_id)
			->where('user_id', Auth::user()->id)
			->count();
	}

	public static function getTotalExerciseReps($date, $exercise_id, $exercise_unit_id)
	{
		return static
			::where('date', $date)
			->where('exercise_id', $exercise_id)
			->where('exercise_unit_id', $exercise_unit_id)
			->where('user_id', Auth::user()->id)
			->sum('quantity');
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

	


}
