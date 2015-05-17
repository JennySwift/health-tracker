app.factory('entries', function ($http) {
	return {

		/**
		 * select
		 */

		getEntries: function ($date) {
			var $url = 'select/entries';
			var $data = {
				date: $date
			};
			
			return $http.post($url, $data);
		}

		/**
		 * insert
		 */
		
		/**
		 * update
		 */
		
		/**
		 * delete
		 */
	};
});
