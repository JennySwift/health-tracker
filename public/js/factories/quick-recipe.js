app.factory('quickRecipe', function ($http) {
	var $object = {};

	$object.formatString = function ($string, $wysiwyg) {
		var $lines = [];
		var $divs;

		//format for Firefox
		if ($string.indexOf('<br>') !== -1) {
			$string = $object.formatForFirefox($string, $wysiwyg);
		}

		//Format for Chrome
		else if ($string.indexOf('<div>') !== -1) {
			$string = $object.formatForChrome($string, $wysiwyg);
		}

		//turn the string into an array of lines
		$divs = $($wysiwyg).children();
		$($divs).each(function () {
			var $line = $(this).html();
			$line = $line.trim();
			if ($line !== "") {
				$lines.push($line);
			}	
		});

		//separate the method from the recipe
		$recipe = $object.separateMethod($lines);

		return $recipe;
	};

	$object.separateMethod = function ($lines) {
		var $items;
		var $method;
		var $recipe;
		var $method_index;

		/**
		 * @VP:
		 * Surely there's a way to do these checks with less code?
		 */

		//Check for the method trigger possibilities
		//First, the lowercase possibilities
		if ($lines.indexOf('method') !== -1) {
			$method_index = $lines.indexOf('method');
		}
		else if ($lines.indexOf('preparation') !== -1) {
			$method_index = $lines.indexOf('preparation');
		}
		else if ($lines.indexOf('directions') !== -1) {
			$method_index = $lines.indexOf('directions');
		}

		//Then, the uppercase possibilities
		if ($lines.indexOf('Method') !== -1) {
			$method_index = $lines.indexOf('Method');
		}
		else if ($lines.indexOf('Preparation') !== -1) {
			$method_index = $lines.indexOf('Preparation');
		}
		else if ($lines.indexOf('Directions') !== -1) {
			$method_index = $lines.indexOf('Directions');
		}

		//Then, the lowercase colon possibilities
		if ($lines.indexOf('method:') !== -1) {
			$method_index = $lines.indexOf('method:');
		}
		else if ($lines.indexOf('preparation') !== -1) {
			$method_index = $lines.indexOf('preparation:');
		}
		else if ($lines.indexOf('directions:') !== -1) {
			$method_index = $lines.indexOf('directions:');
		}
		
		//Then, the uppercase colon possibilities
		if ($lines.indexOf('Method:') !== -1) {
			$method_index = $lines.indexOf('Method:');
		}
		else if ($lines.indexOf('Preparation:') !== -1) {
			$method_index = $lines.indexOf('Preparation:');
		}
		else if ($lines.indexOf('Directions:') !== -1) {
			$method_index = $lines.indexOf('Directions:');
		}

		//If $method_index, there is a method.
		//If not, there is no method.
		//Populate the object to return.
		if ($method_index) {
			$items = $lines.slice(0, $method_index);
			$method = $lines.slice($method_index+1);

			$recipe = {
				items: $items,
				method: $method
			};
		}
		else {
			//There is no method
			$recipe = {
				items: $lines
			};
		}
		
		return $recipe;
	};

	$object.formatForFirefox = function ($string, $wysiwyg) {
		//remove the final and pointless br tag/tags
		while ($string.substr($string.length - 4, 4) === '<br>') {
			//there is a br tag at the end. remove it.
			$string = $string.substring(0, $string.length - 4);
		}

		//change br tags to divs
		var $split = $string.split('<br>');
		var $formatted_string = "";
		for (var i = 0; i < $split.length; i++) {
			$formatted_string += '<div>' + $split[i] + '</div>';
		}
		$string = $formatted_string;
		$($wysiwyg).html($string);

		return $string;
	};

	$object.formatForChrome = function ($string, $wysiwyg) {
		//remove any closing div tags
		while ($string.indexOf('</div>') !== -1) {
			$string = $string.replace('</div>', '');
		}

		//split the string
		$split = $string.split('<div>');

		//turn the string into an array of divs
		$formatted_string = "";	
		for (var j = 0; j < $split.length; j++) {
			//This if check is because when I would run the function a second time, $split had an '' value,
			//causing this next code to create an empty div.
			if ($split[j] !== '') {
				$formatted_string += '<div>' + $split[j] + '</div>';
			}
		}

		$string = $formatted_string;
		$($wysiwyg).html($string);

		return $string;
	};

	$object.populateItemsArray = function ($items) {
		$($items).each(function () {
			$line = this;
			var $item = {};

			//if there is a description, separate the description from the quantity, unit and food
			if ($line.indexOf(',') !== -1) {
				$line = $line.split(',');
				//grab the description, add it to the item so I can remove it from the line
				//so it doesn't get in the way
				$item.description = $line[1].trim();
				$line = $line[0];
			}

			//$line is now just the quantity, unit and food, without the description
			//split $line into an array with quantity, unit and food
			$line = $line.split(' ');
			//Add the quantity, unit and food to the $item
			$item.quantity = $line[0];
			$item.unit = $line[1];
			$item.food = $line[2];

			//Add the item object to the items array
			$items.push($item);
		});

		return $items;
	};

	$object.errorCheck = function ($items) {
		var $line_number = 0;
		var $errors = [];

		$($items).each(function () {
			var $item = this;
			$line_number++;

			if (!$item.quantity || !$item.food || !$item.description) {
				$errors.push('Error on line ' + $line_number);
			}
		});

		return $errors;
	};

	// quantity: function ($string, $index) {
	// 	//Find out how many digits the quantity contains.
	// 	var $end_quantity_index = $index + 1;

	// 	//check if the following characters are numbers, or empty strings (isNan return false for empty string), a decimal point, or a / for a fractional number
	// 	while (!isNaN($string.substr($end_quantity_index, 1)) && $string.substr($end_quantity_index, 1) !== ' ' || $string.substr($end_quantity_index, 1) === '.' || $string.substr($end_quantity_index, 1) === '/') {
	// 		//in other words, the next character is a number, '.' or '/'.
	// 		$end_quantity_index++;
	// 	}

	// 	$quantity = $string.substring($index, $end_quantity_index);

	// 	//check if $quantity is a fraction, and if so, convert to decimal
	// 	if ($quantity.indexOf('/') !== -1) {
	// 		//it is a fraction
	// 		var $parts = $quantity.split('/');
	// 		var $decimal = parseInt($parts[0], 10) / parseInt($parts[1], 10);
	// 		$quantity = $decimal;
	// 	}

	// 	return [$quantity, $end_quantity_index];
	// },
	// unitName: function ($string, $end_quantity_index) {
	// 	$start_unit_index = $end_quantity_index + 1;
	// 	var $end_unit_index = $start_unit_index + 1;
	// 	var $unit_name;

	// 	while ($string.substr($end_unit_index, 1) !== ' ') {
	// 		if ($string.substr($end_unit_index, 1) === '<' || $string.substr($end_unit_index, 1) === ',' || $string.substr($end_unit_index, 1) === '') {
	// 			//there was an error-after the unit there is a tag, comma, or it is the end of the line.
	// 			return {
	// 				error: true
	// 			};
	// 		}
	// 		$end_unit_index++;
	// 	}

	// 	$unit_name = $string.substring($start_unit_index, $end_unit_index);

	// 	return {
	// 		name: $unit_name,
	// 		end_index: $end_unit_index,
	// 		error: false
	// 	};
	// },
	// foodName: function ($string, $end_unit_index) {
	// 	var $start_food_index = $end_unit_index + 1;
	// 	var $end_food_index = $start_food_index + 1;

	// 	while ($string.substr($end_food_index, 1) !== ',' && $string.substr($end_food_index, 1) !== "") {
	// 		//we haven't reached a comma or the end of a line
	// 		$end_food_index++;
	// 	}
		
	// 	var $food_name = $string.substring($start_food_index, $end_food_index);

	// 	return [$food_name, $end_food_index];
	// },
	// description: function ($string, $end_food_index) {
	// 	var $description;

	// 	//check there is a comma
	// 	if ($string.substr($end_food_index, 1) === ',') {
	// 		var $start_description_index = $end_food_index + 1;
	// 		var $end_description_index = $start_description_index + 1;

	// 		while ($string.substr($end_description_index, 1) !== "") {
	// 			$end_description_index++;
	// 		}
			
	// 		$description = $string.substring($start_description_index + 1, $end_description_index);
	// 	}

	// 	return $description;
	// }
	
	return $object;
});