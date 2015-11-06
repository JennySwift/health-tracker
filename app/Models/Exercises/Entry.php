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
     *
     * @param $date
     * @return mixed
     */
    public function calculateSets($date)
    {
        return $this->sets = Entry::forCurrentUser()
            ->where('date', $date)
            ->where('exercise_id', $this->exercise->id)
            ->where('exercise_unit_id', $this->unit->id)
            ->count();
    }

    /**
     *
     * @param $date
     * @return mixed
     */
    public function calculateTotal($date)
    {
        return $this->total = Entry::forCurrentUser()
            ->where('date', $date)
            ->where('exercise_id', $this->exercise->id)
            ->where('exercise_unit_id', $this->unit->id)
            ->sum('quantity');
    }
}
