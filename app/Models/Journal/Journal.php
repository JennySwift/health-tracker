<?php namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Journal extends Model {

	protected $table = 'journal_entries';

	/**
	 * Define relationships
	 */

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	/**
	 * select
	 */

	public static function getJournalEntry($date)
	{
		$entry = static
			::where('date', $date)
			->where('user_id', Auth::user()->id)
			->select('id', 'text')
			->first();

		//for some reason I would get an error if I didn't do this:
		if (isset($entry)) {
			$response = array(
				'id' => $entry->id,
				'text' => $entry->text
			);
		}
		else {
			$response = array();
		}
		
		return $response;
	}

	/**
	 * insert
	 */

	public static function insertOrUpdateJournalEntry($date, $text)
	{
		//check if an entry already exists
		$count = static
			::where('date', $date)
			->where('user_id', Auth::user()->id)
			->count();

		if ($count === 0) {
			//create a new entry
			static::insert([
				'date' => $date,
				'text' => $text,
				'user_id' => Auth::user()->id
			]);
		}
		else {
			//update existing entry
			static
				::where('date', $date)
				->where('user_id', Auth::user()->id)
				->update([
					'text' => $text
				]);
		}
		
	}

	/**
	 * update
	 */
	
	/**
	 * delete
	 */

}