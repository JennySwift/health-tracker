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
