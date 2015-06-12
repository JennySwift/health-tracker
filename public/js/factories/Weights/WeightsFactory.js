app.factory('WeightsFactory', function ($http) {
	return {
		
		/**
		 * select
		 */
		
		/**
		 * insert
		 */
		
		insertWeight: function ($sql_date) {
			var $url = 'insert/weight';
			var $weight = $("#weight").val();

			var $data = {
				date: $sql_date,
				weight: $weight
			};

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