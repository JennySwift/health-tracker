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
		deleteProject: function ($scope, $project, $context) {
            var $url = $project.path;

            // Update the scope with the collection without the project we are deleting

            // Take the context and delete the project from the javascript version of the collection
            // You can access the id of the object with $project.id
            //$scope.projects = the array without the project we deleted;

			return $http.delete($url); // Return same thing here
		}
	};
});
