app.factory('ProjectsFactory', function ($http) {
	return {

		/**
		 * select
		 */
		
		/**
		 * insert
		 */

		insertProject: function ($payer_email, $description, $rate) {
			var $url = '/projects';
			var $data = {
				payer_email: $payer_email,
				description: $description,
				rate: $rate
			};
			
			return $http.post($url, $data);
		},

		/**
		 * update
		 */

		/**
		 * delete
		 */
		
		deleteProject: function ($project) {
            var $url = $project.path;

			return $http.delete($url);
		}

	};
});
