app.factory('journal', function ($http) {
	return {
		/**
		 * select
		 */
		
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