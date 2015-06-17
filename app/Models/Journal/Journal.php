<?php namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Journal extends Model {

	protected $table = 'journal_entries';

    protected $fillable = ['date', 'text', 'user_id'];

    protected $appends = ['path'];

	/**
	 * Define relationships
	 */

	public function user()
	{
		return $this->belongsTo('App\User');
	}

    /**
     * Appends
     */

    /**
     * Return the URL of the resource
     * @return string
     */
    public function getPathAttribute()
    {
        return route('journal.show', $this->id);
    }

	/**
	 * select
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

	/**
	 * insert
	 */

//	public static function insertOrUpdateJournalEntry($date, $text)
//	{
//		//check if an entry already exists
//		$count = static
//			::where('date', $date)
//			->where('user_id', Auth::user()->id)
//			->count();
//
//		if ($count === 0) {
//			//create a new entry
//			static::insert([
//				'date' => $date,
//				'text' => $text,
//				'user_id' => Auth::user()->id
//			]);
//		}
//		else {
//			//update existing entry
//			static
//				::where('date', $date)
//				->where('user_id', Auth::user()->id)
//				->update([
//					'text' => $text
//				]);
//		}
//
//	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */

}
