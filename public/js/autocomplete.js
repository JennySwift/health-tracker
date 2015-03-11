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



// if (matchMedia) {
// 	mediaQuery();
// }

// function mediaQuery () {
// 	var $media_query = window.matchMedia("(max-width: 480px)");

// 	if ($media_query.matches) {
// 		$("body").css('color', 'red');
// 	}
// }

enquire.register("screen and (max-width: 890px", {
	match: function () {
		$("#avg-calories-for-the-week-text").text('Avg: ');
	},
	unmatch: function () {
		$("#avg-calories-for-the-week-text").text('Avg calories (last 7 days): ');
	}
});



function autocompleteExercise () {
	var $exercise = $("#exercise").val();
	$.ajax({
		url: 'ajax/autocomplete-exercise.php',
		data: {
			exercise: $exercise
		},
	})
	.done(function(response) {
		var $response = $.parseJSON(response);
		var $exercises = $response.exercises;
		var $html = '';

		$($exercises).each(function () {
			var $id = this.id;
			var $exercise = this.name;
			$html += '<div data-exercise-id="' + $id + '" class="autocomplete-dropdown-item dropdown-exercise">' + $exercise + '</div>';
		});
		$("#exercise-autocomplete").html($html);
		$(".dropdown-exercise").first().addClass('selected');
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}

