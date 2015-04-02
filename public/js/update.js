app.factory('update', function ($http) {
	return {
		defaultUnit: function ($food_id, $unit_id) {
			var $url = 'update/defaultUnit';

			var $data = {
				food_id: $food_id,
				unit_id: $unit_id
			};

			return $http.post($url, $data);
		},
		exerciseSeries: function ($exercise_id, $series_id) {
			var $url = 'update/exerciseSeries';
			var $data = {
				exercise_id: $exercise_id,
				series_id: $series_id
			};
			
			return $http.post($url, $data);
		},
		exerciseStepNumber: function ($exercise_id) {
			var $url = 'update/exerciseStepNumber';
			var $step_number = $("#exercise-step-number").val();
			var $data = {
				exercise_id: $exercise_id,
				step_number: $step_number
			};

			$("#exercise-step-number").val("");
			
			return $http.post($url, $data);
		},
		defaultExerciseQuantity: function ($id) {
			var $quantity = $("#default-unit-quantity").val();
			var $url = 'update/defaultExerciseQuantity';
			var $data = {
				id: $id,
				quantity: $quantity
			};
			
			return $http.post($url, $data);
		},
		// journalEntry: function ($sql_date, $id, $text) {
		// 	var $url = 'update/journalEntry';
		// 	var $data = {
		// 		date: $sql_date,
		// 		id: $id,
		// 		text: $text
		// 	};
			
		// 	return $http.post($url, $data);
		// },
		calories: function ($food_id, $unit_id, $calories) {
			var $url = 'update/calories';

			var $data = {
				food_id: $food_id,
				unit_id: $unit_id,
				calories: $calories
			};

			return $http.post($url, $data);
		},
		defaultExerciseUnit: function ($exercise_id, $default_exercise_unit_id) {
			var $url = 'update/defaultExerciseUnit';
			var $data = {
				exercise_id: $exercise_id,
				default_exercise_unit_id: $default_exercise_unit_id
			};
			
			return $http.post($url, $data);
		}
	};
});