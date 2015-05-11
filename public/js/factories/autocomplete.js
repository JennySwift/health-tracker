app.factory('autocomplete', function ($http) {
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

	return $object;
});

// ===========================end factory===========================

$("#food").val("");
$("#weight").val("");



