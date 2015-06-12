app.factory('AutocompleteFactory', function ($http) {
	var $object = {};
	$object.autocompleteUpArrow = function ($array) {
		// if ($(".selected").prev(".autocomplete-dropdown-item").length > 0) {
		// 	//there is an item before the selected one
		// 	$(".selected").prev(".autocomplete-dropdown-item").addClass('selected');
		// 	$(".selected").last().removeClass('selected');
		// }

		//find the selected object in the array
		var $selected = _.findWhere($array, {selected: true});
		//get its index
		var $index = $array.indexOf($selected);
		var $prev_index = $index - 1;
		if ($array[$prev_index]) {
			//there is an item before the selected one
			var $prev = $array[$prev_index];
			$prev.selected = true;
			$selected.selected = false;
		}
	};

	/**
	 * For when the dropdown options are in an array
	 * @param  {[type]} $array [description]
	 * @return {[type]}        [description]
	 */
	$object.autocompleteDownArrow = function ($array) {
		// if ($(".selected").next(".autocomplete-dropdown-item").length > 0) {
		// 	//there is an item after the selected one
		// 	$(".selected").next(".autocomplete-dropdown-item").addClass('selected');
		// 	$(".selected").first().removeClass('selected');
		// }

		//find the selected object in the array
		var $selected = _.findWhere($array, {selected: true});
		//get its index
		var $index = $array.indexOf($selected);
		var $next_index = $index + 1;
		if ($array[$next_index]) {
			//there is an item after the selected one
			var $next = $array[$next_index];
			$next.selected = true;
			$selected.selected = false;
		}
	};

	/**
	 * For when the dropdown options are in an object.
	 * I haven't finished it.
	 * @param  {[type]} $array [description]
	 * @return {[type]}        [description]
	 */
	// $object.autocompleteDownArrowForObject = function ($object) {
	// 	//find the selected object in the array
	// 	var $selected = _.findWhere($object, {selected: true});

	// 	//get its index
	// 	var $index = $array.indexOf($selected);
	// 	var $next_index = $index + 1;
	// 	if ($array[$next_index]) {
	// 		//there is an item after the selected one
	// 		var $next = $array[$next_index];
	// 		$next.selected = true;
	// 		$selected.selected = false;
	// 	}
	// };

	$object.exercise = function () {
		var $typing = $("#exercise").val();
		var $url = 'select/autocompleteExercise';
		var $data = {
			exercise: $typing
		};
		
		return $http.post($url, $data);
	};

	$object.food = function ($typing) {
		var $url = 'select/autocompleteFood';
		var $data = {
			typing: $typing
		};
		
		return $http.post($url, $data);
	};

	$object.menu = function () {
		var $typing = $("#menu").val();
		var $url = 'select/autocompleteMenu';
		var $data = {
			typing: $typing
		};
		
		return $http.post($url, $data);
	};

	// menu: function ($menu) {
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

	return $object;
});

// ===========================end factory===========================

$("#food").val("");
$("#weight").val("");



