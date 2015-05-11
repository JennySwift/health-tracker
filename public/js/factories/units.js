app.factory('units', function ($http) {
	return {
		
		/**
		 * select
		 */
		
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
		insertUnitInCalories: function ($food_id, $unit_id, $checked_previously) {
			var $url = 'insert/unitInCalories';

			var $data = {
				food_id: $food_id,
				unit_id: $unit_id,
				checked_previously: $checked_previously
			};

			return $http.post($url, $data);
		},

		/**
		 * update
		 */
		
		updateDefaultUnit: function ($food_id, $unit_id) {
			var $url = 'update/defaultUnit';

			var $data = {
				food_id: $food_id,
				unit_id: $unit_id
			};

			return $http.post($url, $data);
		},

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
		},
		deleteUnitFromCalories: function ($food_id, $unit_id) {
			var $url = 'delete/unitFromCalories';
			var $data = {
				food_id: $food_id,
				unit_id: $unit_id
			};
			
			return $http.post($url, $data);
		},
	};
});