app.factory('UnitsFactory', function ($http) {
	return {
		
		/**
		 * select
		 */
		
		getAllUnits: function () {
			var $url = 'select/allUnits';
			
			return $http.post($url);
		},

		/**
		 * insert
		 */
		
		insertExerciseUnit: function () {
			var $url = 'insert/exerciseUnit';
			var $name = $("#create-new-exercise-unit").val();
			
			var $data = {
				name: $name
			};

			$("#create-new-exercise-unit").val("");		
			return $http.post($url, $data);
		},
		insertFoodUnit: function () {
			var $url = 'insert/foodUnit';
			var $name = $("#create-new-food-unit").val();
			
			var $data = {
				name: $name
			};

			$("#create-new-food-unit").val("");		
			return $http.post($url, $data);
		},

		/**
		 * update
		 */

		/**
		 * delete
		 */

		deleteExerciseUnit: function ($id) {
			if (confirm("Are you sure you want to delete this unit?")) {
				var $url = 'delete/exerciseUnit';
				var $data = {
					id: $id
				};
				
				return $http.post($url, $data);
			}
		},
		deleteFoodUnit: function ($id) {
			if (confirm("Are you sure you want to delete this unit?")) {
				var $url = 'delete/foodUnit';
				var $data = {
					id: $id
				};
				
				return $http.post($url, $data);
			}
		}
	};
});