app.factory('QuickRecipeFactory', function ($http) {
	var $object = {};

	$object.formatString = function ($string, $wysiwyg) {
		var $lines = [];
		var $divs;

		//Format for any browser (hopefully)
		var $string_and_array = $object.formatForAnyBrowser($string, $wysiwyg);
		$string = $string_and_array.string;
		var $array = $string_and_array.array;

		//trim the items in the array
		$($array).each(function () {
			this.trim();
		});

		//separate the method from the recipe
		$recipe = $object.separateMethod($array);

		return $recipe;

		//format for Firefox
		// if ($string.indexOf('<br>') !== -1) {
		// 	$string = $object.formatForFirefox($string, $wysiwyg);
		// }

		//Format for Chrome
		// else if ($string.indexOf('<div>') !== -1) {
		// 	$string = $object.formatForChrome($string, $wysiwyg);
		// }

		//turn the string into an array of lines
		// $divs = $($wysiwyg).children();
		// $($divs).each(function () {
		// 	var $line = $(this).html();
		// 	$line = $line.trim();
		// 	if ($line !== "") {
		// 		$lines.push($line);
		// 	}	
		// });
	};

	/**
	 * $lines is an array of all the lines in the wysywig.
	 * We want to return an object containing the item lines, and the method lines, separate from each other.
	 * @param  {[type]} $lines [description]
	 * @return {[type]}        [description]
	 */
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

	/**
	 * The $string may contain unwanted br tags and both opening and closing div tags.
	 * Format the string so into a string of div tags to populate the html of the wysiwyg.
	 * And create an array from the $string.
	 * Return both the formatted string and the array.
	 * @param  {[type]} $string  [description]
	 * @param  {[type]} $wysiwyg [description]
	 * @return {[type]}          [description]
	 */
	$object.formatForAnyBrowser = function ($string, $wysiwyg) {
		//Remove any closing div tags and replace any opening div tags with a br tag.
		while ($string.indexOf('<div>') !== -1 || $string.indexOf('</div>') !== -1) {
			$string = $string.replace('<div>', '<br>').replace('</div>', '');
		}

		//turn the string into an array of divs by first splitting at the br tags
		var $array = $string.split('<br>');

		//remove any empty elements from the array
		$array = _.without($array, '');

		var $formatted_string = "";

		//make $formatted_string a string with div tags
		for (var j = 0; j < $array.length; j++) {
			$formatted_string += '<div>' + $array[j] + '</div>';
		}
		
		$string = $formatted_string;
		$($wysiwyg).html($string);

		return {
			string: $string,
			array: $array
		};
	};

	// $object.formatForFirefox = function ($string, $wysiwyg) {
	// 	//remove the final and pointless br tag/tags
	// 	while ($string.substr($string.length - 4, 4) === '<br>') {
	// 		//there is a br tag at the end. remove it.
	// 		$string = $string.substring(0, $string.length - 4);
	// 	}

	// 	//change br tags to divs
	// 	var $split = $string.split('<br>');
	// 	var $formatted_string = "";
	// 	for (var i = 0; i < $split.length; i++) {
	// 		//First check there aren't any divs. When I tried editing a recipe in Chrome, the html of the existing method contained br tags, but when I added a new line it made it a div tag, messing up my code.
	// 		if ($split[i].indexOf('<div>' !== -1)) {
	// 			//There is an opening div tag. Get rid of it.
	// 			$split[i] = $split[i].replace('<div>', '');
	// 		}
	// 		if ($split[i].indexOf('</div>' !== -1)) {
	// 			//There is an closing div tag. Get rid of it.
	// 			$split[i] = $split[i].replace('</div>', '');
	// 		}

	// 		$formatted_string += '<div>' + $split[i] + '</div>';
	// 	}
	// 	$string = $formatted_string;
	// 	$($wysiwyg).html($string);

	// 	return $string;
	// };

	// $object.formatForChrome = function ($string, $wysiwyg) {
	// 	//remove any closing div tags
	// 	while ($string.indexOf('</div>') !== -1) {
	// 		$string = $string.replace('</div>', '');
	// 	}

	// 	//split the string
	// 	$split = $string.split('<div>');

	// 	//turn the string into an array of divs
	// 	$formatted_string = "";	
	// 	for (var j = 0; j < $split.length; j++) {
	// 		//This if check is because when I would run the function a second time, $split had an '' value,
	// 		//causing this next code to create an empty div.
	// 		if ($split[j] !== '') {
	// 			$formatted_string += '<div>' + $split[j] + '</div>';
	// 		}
	// 	}

	// 	$string = $formatted_string;
	// 	$($wysiwyg).html($string);

	// 	return $string;
	// };

	/**
	 * $items is an array of strings.
	 * The string should include the quantity, unit, food, and description, providing the user has entered them.
	 * We want to take each string and turn it into an object with food, unit, quantity and description properties.
	 * Then return the new array of objects.
	 * @param  {[type]} $items [description]
	 * @return {[type]}        [description]
	 */
	$object.populateItemsArray = function ($items) {
		var $formatted_items = [];
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
			$formatted_items.push($item);
		});

		return $formatted_items;
	};

	/**
	 * Return an array of errors for each line that does not have a quantity, unit and food
	 * @param  {[type]} $items [description]
	 * @return {[type]}        [description]
	 */
	$object.errorCheck = function ($items) {
		var $line_number = 0;
		var $errors = [];
		var $checked_quantity;

		$($items).each(function () {
			var $item = this;
			$line_number++;

			if (!$item.quantity || !$item.unit || !$item.food) {
				$errors.push('Quantity, unit, and food have not all been included on line ' + $line_number);
				$("#quick-recipe > div").eq($line_number-1).css('background', 'red');
			}
			//The line contains quantity, unit and food.
			//Check the quantity is valid.
			else {
				$checked_quantity = $object.validQuantityCheck($item.quantity);
				if (!$checked_quantity) {
					//Quantity is invalid
					$errors.push('Quantity is invalid on line ' + $line_number);
					$("#quick-recipe > div").eq($line_number-1).css('background', 'red');
				}
				else {
					// Quantity is valid and if it was a fraction, it has now been converted to a decimal.
					$item.quantity = $checked_quantity;
				}
			}
		});

		return {
			items: $items,
			errors: $errors
		};
	};

	/**
	 * Check the quantity for any invalid characters.
	 * If the quantity is a fraction, convert it to a decimal.
	 * @param  {[type]} $quantity [description]
	 * @return {[type]}           [description]
	 */
	$object.validQuantityCheck = function ($quantity) {
		for (var i = 0; i < $quantity.length; i++) {
			var $character = $quantity[i];

			if (isNaN($character) && $character !== '.' && $character !== '/') {
				//$character is not a number, '.', or '/'. The quantity is invalid.
				$quantity = false;
			}
			else {
				$quantity = $object.convertQuantityToDecimal($quantity);
			}
		}

		return $quantity;
	};

	/**
	 * Check if $quantity is a fraction, and if so, convert to decimal
	 * @param  {[type]} $quantity [description]
	 * @return {[type]}           [description]
	 */
	$object.convertQuantityToDecimal = function ($quantity) {
		if ($quantity.indexOf('/') !== -1) {
			//it is a fraction
			var $parts = $quantity.split('/');
			var $decimal = parseInt($parts[0], 10) / parseInt($parts[1], 10);
			$quantity = $decimal;
		}

		return $quantity;
	};
	
	return $object;
});