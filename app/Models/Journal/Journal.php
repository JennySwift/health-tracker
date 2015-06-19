<?php namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Model;
use Auth;

/**
 * Class Journal
 * @package App\Models\Journal
 */
class Journal extends Model {

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
     * Return the URL of the resource
     * @return string
     */
    public function getPathAttribute()
    {
        return route('journal.show', $this->id);
    }

    /**
     *
     * @param $date
     * @return array
     */
    public static function getJournalEntry($date)
	{
		$entry = static
			::where('date', $date)
			->where('user_id', Auth::user()->id)
			->first();

		if (!isset($entry)) {
			return [];
		}
		else {
			return $entry;
		}
	}

}
