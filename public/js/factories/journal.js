app.factory('journal', function ($http) {
	return {
		/**
		 * select
		 */
		
		getJournalEntry: function ($sql_date) {
			var $url = 'select/getJournalEntry';
			var $data = {
				date: $sql_date
			};
			
			return $http.post($url, $data);
		},
		
		/**
		 * insert
		 */
		
		insertJournalEntry: function ($sql_date) {
			var $url = 'insert/journalEntry';
			var $text = $("#journal-entry").html();
			var $data = {
				date: $sql_date,
				text: $text
			};

			console.log('text from js: ' + $text);
			
			return $http.post($url, $data);
		},
		
		/**
		 * update
		 */
		
		/**
		 * delete
		 */

		
	};
});