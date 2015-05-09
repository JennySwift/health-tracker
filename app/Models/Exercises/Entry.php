<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Exercises\Exercise;
use App\Models\Exercises\Entry as ExerciseEntry;

class Entry extends Model {

	protected $table = 'exercise_entries';

	/**
	 * Define relationships
	 */

	public function user () {
	    return $this->belongsTo('App\User');
	}

	public function exercise () {
	    return $this->belongsTo('App\Models\Exercises\Exercise');
	}

	public function unit () {
	    return $this->belongsTo('App\Models\Units\Unit', 'exercise_unit_id');
	}

	/**
	 * select
	 */
	
	public static function getSpecificExerciseEntries ($date, $exercise_id, $exercise_unit_id) {
		/**
		 * This works. Methods one and/or two (see below) need to accomplish the same thing this query accomplishes. (They need the unit.)
		 * (Although, even when it is working in my app, postman is returning an empty array. And I don't think I entered the wrong things in postman since method one below did not return an empty array.)
		 */

		$entries = ExerciseEntry
			::where('date', $date)
			->where('exercise_id', $exercise_id)
			->where('exercise_unit_id', $exercise_unit_id)
			->join('exercises', 'exercise_entries.exercise_id', '=', 'exercises.id')
			->join('units', 'exercise_entries.exercise_unit_id', '=', 'units.id')
			->select('exercise_id', 'quantity', 'exercises.name', 'units.name AS unit_name', 'exercise_entries.id AS entry_id')
			->orderBy('exercises.name', 'asc')
			->get();

		/**
		 * method one
		 * I tried this but the query is returning various exercises for some reason.
		 */
		
		// $exercise = Exercise::find($request->get('exercise_id'));
		// $entries = $exercise->with(['entries' => function($query) use ($date)
		// {
		//     $query->where('date', $date);
		// }])->get();
			
		/**
		 * method two
		 * Error: Missing argument 2 for Illuminate\Database\Query\Builder::whereDate()
		 */
		
		// $entries = ExerciseEntry::whereDate($date)->with(['exercise' => function($query) use ($exercise_id) {
		//     $query->whereId($exercise_id);
		//     $query->orderBy('name');
		// }])->get();

		return $entries;
	}
	
	public static function getSeriesEntries($exercise_ids) {
		return static
			::whereIn('exercise_id', $exercise_ids)
			->join('exercises', 'exercise_entries.exercise_id', '=', 'exercises.id')
			->join('units', 'exercise_entries.exercise_unit_id', '=', 'units.id')
			->select('date', 'exercises.id as exercise_id', 'exercises.name as exercise_name', 'exercises.description', 'exercises.step_number', 'quantity', 'exercise_unit_id', 'units.name as unit_name')
			->orderBy('date', 'desc')
			->get();
	}

	public static function getExerciseSets ($date, $exercise_id, $exercise_unit_id) {
		return static
			::where('date', $date)
			->where('exercise_id', $exercise_id)
			->where('exercise_unit_id', $exercise_unit_id)
			->where('user_id', Auth::user()->id)
			->count();
	}

	public static function getTotalExerciseReps ($date, $exercise_id, $exercise_unit_id) {
		return static
			::where('date', $date)
			->where('exercise_id', $exercise_id)
			->where('exercise_unit_id', $exercise_unit_id)
			->where('user_id', Auth::user()->id)
			->sum('quantity');
	}

	public static function getExerciseEntries ($date) {
		$entries = static
			::where('date', $date)
			->where('exercise_entries.user_id', Auth::user()->id)
			->join('exercises', 'exercise_entries.exercise_id', '=', 'exercises.id')
			->join('units', 'exercise_entries.exercise_unit_id', '=', 'units.id')
			->select('exercise_id', 'quantity', 'exercises.description', 'exercise_unit_id', 'exercises.name', 'units.name AS unit_name', 'units.id AS unit_id', 'exercise_entries.id AS entry_id')
			->orderBy('exercises.name', 'asc')
			->orderBy('unit_name', 'asc')
			->get();

		return static::compactExerciseEntries($entries, $date);
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

	public static function compactExerciseEntries ($entries, $date) {
		$array = array();
		foreach ($entries as $entry) {
			$exercise_id = $entry->exercise_id;
			$exercise_unit_id = $entry->exercise_unit_id;
			$counter = 0;

			$total = ExerciseEntry::getTotalExerciseReps($date, $exercise_id, $exercise_unit_id);

			$sets = ExerciseEntry::getExerciseSets($date, $exercise_id, $exercise_unit_id);

			//check to see if the array already has the exercise entry so it doesn't appear as a new entry for each set of exercises
			foreach ($array as $item) {
				if ($item['name'] === $entry->name && $item['unit_name'] === $entry->unit_name) {
					//the exercise with unit already exists in the array so we don't want to add it again
					$counter++;
				}
			}
			if ($counter === 0) {
				$array[] = array(
					'exercise_id' => $entry->exercise_id,
					'name' => $entry->name,
					'description' => $entry->description,
					'quantity' => $entry->quantity,
					'unit_name' => $entry->unit_name,
					'sets' => $sets,
					'total' => $total,
					'unit_id' => $entry->unit_id,
					'default_exercise_unit_id' => Exercise::getDefaultExerciseUnitId($exercise_id),
					// 'default_quantity' => $default_quantity
				);
			}	
		}
		return $array;
	}


}
