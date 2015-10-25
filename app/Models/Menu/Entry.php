<?php namespace App\Models\Menu;

use App\Traits\Models\Relationships\OwnedByUser;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Debugbar;
use DB;

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
		return $this->belongsTo('App\Models\Menu\Food');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipe()
    {
        return $this->belongsTo('App\Models\Menu\Recipe');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
    {
        return $this->belongsTo('App\Models\Units\Unit', 'unit_id');
    }

    /**
     * Get the calories for the entry
     * @VP:
     * Is there a better way of getting the calories?
     * With some sort of relationship?
     * @return mixed
     */
    public function getCalories()
    {
        $caloriesForOneUnit = DB::table('food_unit')->where('food_id', $this->food->id)
            ->where('unit_id', $this->unit->id)
            ->pluck('calories');

        return $caloriesForOneUnit * $this->quantity;
    }

}
