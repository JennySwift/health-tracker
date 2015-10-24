<?php namespace App\Models\Journal;

use App\Traits\Models\Relationships\OwnedByUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Auth;

/**
 * Class Journal
 * @package App\Models\Journal
 */
class Journal extends Model {

    use OwnedByUser;

    /**
     * @var string
     */
    protected $table = 'journal_entries';

    /**
     * @var array
     */
    protected $fillable = ['date', 'text', 'user_id'];

    /**
     * @var array
     */
    protected $appends = ['path', 'date'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
	{
		return $this->belongsTo('App\User');
	}

    /**
     * Return the URL of the resource
     * @return string
     */
    public function getPathAttribute()
    {
        return route('api.journal.show', $this->id);
    }

    /**
     * Get date attribute
     * @return string
     */
    public function getDateAttribute()
    {
        if (isset($this->date)) {
            return Carbon::createFromFormat('Y-m-d', $this->attributes['date'])->format('d/m/y');
        }
    }

}
