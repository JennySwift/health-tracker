app.factory('JournalFactory', function ($http) {
	return {
		/**
		 * select
		 */
		
		getJournalEntry: function ($sql_date) {
            return $http.get('/journal/' + $sql_date);
		},
		
		/**
		 * insert
		 */
		
		insertJournalEntry: function ($sql_date) {
            var $url = '/journal';
            var $text = $("#journal-entry").html();
            var $data = {
                date: $sql_date,
                text: $text
            };

            return $http.post($url, $data);
		},
		
		/**
		 * update
		 */

        updateJournalEntry: function ($journal) {
            var $url = $journal.path;
            var $text = $("#journal-entry").html();
            var $data = {
                text: $text
            };

            return $http.put($url, $data);
        }
		
		/**
		 * delete
		 */

		
	};
});