<?php namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Journal extends Model {

	protected $table = 'journal_entries';

	public static function getJournalEntry ($date) {
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

	public static function insertOrUpdateJournalEntry ($date, $text) {
		//check if an entry already exists
		$count = static
			::where('date', $date)
			->where('user_id', Auth::user()->id)
			->count();

		if ($count === 0) {
			//create a new entry
			DB::table('journal_entries')
				->insert([
					'date' => $date,
					'text' => $text,
					'user_id' => Auth::user()->id
				]);
		}
		else {
			//update existing entry
			DB::table('journal_entries')
				->where('date', $date)
				->where('user_id', Auth::user()->id)
				->update([
					'text' => $text
				]);
		}
		
	}

}
