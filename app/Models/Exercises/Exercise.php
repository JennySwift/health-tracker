<?php namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;
use Auth;
use DB;
use Carbon\Carbon;
use Debugbar;

use App\Models\Tags\Tag;
use App\Models\Exercises\Series;
use App\Models\Exercises\Entry as ExerciseEntry;

/**
 * Class Exercise
 * @package App\Models\Exercises
 */
class Exercise extends Model {

	use OwnedByUser;

    /**
     * @var array
     */
    protected $fillable = ['name', 'default_unit_id', 'description', 'default_quantity', 'step_number', 'series_id'];

    /**
     * @var array
     */
    protected $appends = ['path'];

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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function defaultUnit()
    {
        return $this->belongsTo('App\Models\Units\Unit', 'default_unit_id', 'id');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function series()
	{
	    return $this->belongsTo('App\Models\Exercises\Series');
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
     *
     * @return mixed
     */
    public function tags()
	{
		return $this->belongsToMany('App\Models\Tags\Tag', 'taggables', 'taggable_id', 'tag_id')->where('taggable_type', 'exercise');
	}

    /**
     * Return the URL of the project
     * it needs to be called getFieldAttribute
     * @return string
     */
    public function getPathAttribute()
    {
        return route('api.exercises.show', $this->id);
    }

	/**
	 * Get all exercise entries that belong to a series.
	 * Calculate the number of days ago,
	 * the number of reps,
	 * and the number of sets.
	 * If entries share the same exercise, date, and unit, compact them into one item.
	 * @param  [type] $series [description]
	 * @return [type]         [description]
	 */
	public static function getExerciseSeriesHistory($series)
	{
		//get all exercises in the series
		$exercise_ids = $series->exercises->lists('id');

		//get all entries in the series
		$entries = $series->entries()	
			->select(
				'date',
				'exercises.id as exercise_id',
				'exercises.name',
				'exercises.description',
				'exercises.step_number',
				'quantity',
				'exercise_unit_id')
			->with(['unit' => function($query) {
				$query->select('name', 'id');
			}])
			->orderBy('date', 'desc')->get();

		//Populate an array to return
		$array = static::compactExerciseEntries($entries);
		
		return $array;
	}

	/**
	 * For getExerciseSeriesHistory.
	 * If entries share the same exercise, date, and unit, compact them into one item.
	 * @param  [type] $entries [description]
	 * @return [type]          [description]
	 */
	public static function compactExerciseEntries($entries)
	{
		//create an array to return
		$array = [];

		//populate the array
		foreach ($entries as $entry) {
			$sql_date = $entry->date;
			$date = static::convertDate($sql_date, 'user');
			$counter = 0;

			//check to see if the array already has the exercise entry
			//so it doesn't appear as a new entry for each set of exercises
			if (count($array) > 0) {
				foreach ($array as $item) {
					if ($item['date'] === $date && $item['name'] === $entry->name && $item['unit_name'] === $entry->unit->name) {
						//the exercise with unit already exists in the array
						//so we don't want to add it again
						$counter++;
					}
				}
			}
			if ($counter === 0) {
				$array[] = array(
					'date' => $date,
					'days_ago' => static::getHowManyDaysAgo($sql_date),
					'id' => $entry->exercise_id,
					'name' => $entry->name,
					'description' => $entry->description,
					'step_number' => $entry->step_number,
					'unit_name' => $entry->unit->name,
					'sets' => ExerciseEntry::getExerciseSets($sql_date, $entry->exercise_id, $entry->exercise_unit_id),
					'total' => ExerciseEntry::getTotalExerciseReps($sql_date, $entry->exercise_id, $entry->exercise_unit_id),
					'quantity' => $entry->quantity,
				);
			}
		}

		return $array;
	}

    /**
     *
     * @param $date
     * @param $for
     * @return string|static
     */
    public static function convertDate($date, $for)
	{
		$date = Carbon::createFromFormat('Y-m-d', $date);

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
		$now = Carbon::now();
		$date = Carbon::createFromFormat('Y-m-d', $date);
		$diff = $now->diff($date);
		$days_ago = $diff->days;
		return $days_ago;
	}

}
