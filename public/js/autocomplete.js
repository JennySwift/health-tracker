app.factory('autocomplete', function ($http) {
	var $object = {};
	$object.autocompleteUpArrow = function () {
		if ($(".selected").prev(".autocomplete-dropdown-item").length > 0) {
			//there is an item before the selected one
			$(".selected").prev(".autocomplete-dropdown-item").addClass('selected');
			$(".selected").last().removeClass('selected');
		}
	};
	$object.autocompleteDownArrow = function () {
		if ($(".selected").next(".autocomplete-dropdown-item").length > 0) {
			//there is an item after the selected one
			$(".selected").next(".autocomplete-dropdown-item").addClass('selected');
			$(".selected").first().removeClass('selected');
		}
	};
	$object.autocompleteFood = function ($foods, $typing) {
		$food_autocomplete = [];

		for (var i = 0; i < $foods.length; i++) {
			var $iteration = $foods[i];
			var $iteration_name = $iteration.food_name.toLowerCase();
			if ($iteration_name.indexOf($typing.toLowerCase()) !== -1) {
				$food_autocomplete.push($iteration);
			}
		}
		return $food_autocomplete;
	};
	$object.autocompleteMenu = function ($menu, $typing) {
		$menu_autocomplete = [];

		for (var i = 0; i < $menu.length; i++) {
			var $iteration = $menu[i];
			var $iteration_name = $iteration.name.toLowerCase();
			if ($iteration_name.indexOf($typing.toLowerCase()) !== -1) {
				$menu_autocomplete.push($iteration);
			}
		}
		return $menu_autocomplete;
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

