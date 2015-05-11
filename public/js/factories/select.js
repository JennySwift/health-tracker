app.factory('select', function ($http) {
	return {
		pageLoad: function ($sql_date) {
			var $url = 'select/pageLoad';
			var $data = {
				date: $sql_date
			};
			
			return $http.post($url, $data);
		},
		
		autocompleteExercise: function () {
			var $typing = $("#exercise").val();
			var $url = 'select/autocompleteExercise';
			var $data = {
				exercise: $typing
			};
			
			return $http.post($url, $data);
		},
		autocompleteFood: function ($typing) {
			var $url = 'select/autocompleteFood';
			var $data = {
				typing: $typing
			};
			
			return $http.post($url, $data);
		},
		autocompleteMenu: function () {
			var $typing = $("#menu").val();
			var $url = 'select/autocompleteMenu';
			var $data = {
				typing: $typing
			};
			
			return $http.post($url, $data);
		},
		// autocompleteMenu: function ($menu) {
		// 	var $typing = $("#food").val();
		// 	var $menu_autocomplete = [];

		// 	for (var i = 0; i < $menu.length; i++) {
		// 		var $iteration = $menu[i];
		// 		var $iteration_name = $iteration.name.toLowerCase();
		// 		if ($iteration_name.indexOf($typing.toLowerCase()) !== -1) {
		// 			$menu_autocomplete.push($iteration);
		// 		}
		// 	}
		// 	return $menu_autocomplete;
		// },
		getEntries: function ($sql_date) {
			var $url = 'select/entries';
			var $data = {
				date: $sql_date
			};
			
			return $http.post($url, $data);
		},	
	};
});