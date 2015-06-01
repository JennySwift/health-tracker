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
			
			return $http.post($url, $data);

			//This didn't seem to do much
			// return $http.post($url, $data).
			// success(function(data, status, headers, config) {
			//    // this callback will be called asynchronously
			//    // when the response is available
			//    data = 'something';
			//  }).
			//  error(function(data, status, headers, config) {
			//    // called asynchronously if an error occurs
			//    // or server returns response with an error status.
			//    data = 'We have an error';
			//  });
		},
		
		/**
		 * update
		 */
		
		/**
		 * delete
		 */

		
	};
});