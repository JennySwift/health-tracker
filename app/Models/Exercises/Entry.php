<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;
use Auth;
use App\Models\Exercises\Exercise;
use App\Models\Exercises\Entry as ExerciseEntry;
use App\Models\Units\Unit;

/**
 * Class Entry
 * @package App\Models\Exercises
 */
class Entry extends Model {

	use OwnedByUser;

    /**
     * @var array
     */
    protected $fillable = ['date', 'quantity'];

    /**
     * @var string
     */
    protected $table = 'exercise_entries';

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
    public function exercise()
	{
	    return $this->belongsTo('App\Models\Exercises\Exercise');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
	{
	    return $this->belongsTo('App\Models\Units\Unit', 'exercise_unit_id');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entries()
	{
		return $this->hasMany('App\Models\Exercises\Entry');
	}

    /**
     * Get all exercise entries belonging to the user on a specific date
     * @param $date
     * @return array
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
     * @param $entries
     * @param $date
     * @return array
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
     * @param $date
     * @param $exercise
     * @param $exercise_unit_id
     * @return array
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

    /**
     *
     * @param $date
     * @param $exercise_id
     * @param $exercise_unit_id
     * @return mixed
     */
    public static function getExerciseSets($date, $exercise_id, $exercise_unit_id)
	{
		return static
			::where('date', $date)
			->where('exercise_id', $exercise_id)
			->where('exercise_unit_id', $exercise_unit_id)
			->where('user_id', Auth::user()->id)
			->count();
	}

    /**
     *
     * @param $date
     * @param $exercise_id
     * @param $exercise_unit_id
     * @return mixed
     */
    public static function getTotalExerciseReps($date, $exercise_id, $exercise_unit_id)
	{
		return static
			::where('date', $date)
			->where('exercise_id', $exercise_id)
			->where('exercise_unit_id', $exercise_unit_id)
			->where('user_id', Auth::user()->id)
			->sum('quantity');
	}
}
